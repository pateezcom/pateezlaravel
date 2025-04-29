<?php

namespace App\Http\Controllers\Admin\Settings\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings\Language\Language;
use App\Models\Admin\Settings\Language\Translation;

/**
 * Translation Controller
 * Çeviri Kontrolcüsü
 * 
 * This controller handles translation management operations.
 * Bu kontrolcü çeviri yönetimi işlemlerini yönetir.
 */
class TranslationController extends Controller
{
    /**
     * Display the translation edit page
     * Çeviri düzenleme sayfasını görüntüler
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Check language
        // Dili kontrol et
        $language = Language::find($id);

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];
        
        // Get all translations
        // Tüm çevirileri getir
        $translations = Translation::where('language_id', $id)
                                 ->orderBy('key')
                                 ->paginate(50);

        return view('content.admin.settings.language.translations', [
            'pageConfigs' => $pageConfigs,
            'language' => $language,
            'translations' => $translations,
            'activeMenu' => 'admin.settings.languages'
        ]);
    }

    /**
     * Update translations
     * Çevirileri günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check language
        // Dili kontrol et
        $language = Language::find($id);

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        // Get translations from request
        // Gelen çevirileri al
        $translations = $request->input('translations', []);

        try {
            // Update each translation
            // Her çeviriyi güncelle
            foreach ($translations as $key => $value) {
                Translation::where('language_id', $id)
                         ->where('key', $key)
                         ->update([
                             'value' => $value,
                             'updated_at' => now()
                         ]);
            }
            
            // Clear translation cache
            // Çeviri önbelleğini temizle
            if (method_exists(app('translator'), 'resetCache')) {
                app('translator')->resetCache($language->code);
            }

            // Return successful response - for AJAX request
            // Başarılı yanıt döndür - AJAX isteği için
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Çeviriler başarıyla güncellendi'
                ]);
            }

            // For normal form submission
            // Normal form gönderimi için
            return redirect()->back()->with('success', 'Çeviriler başarıyla güncellendi');
        } catch (\Exception $e) {
            // Log error
            // Hatayı loglama
            Log::error('Çeviri güncellenirken hata: ' . $e->getMessage());

            // For AJAX request
            // AJAX isteği için
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Çeviriler güncellenirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }

            // For normal form submission
            // Normal form gönderimi için
            return redirect()->back()->with('error', 'Çeviriler güncellenirken bir hata oluştu');
        }
    }

    /**
     * Search translations
     * Çevirilerde arama yapar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $id)
    {
        // Check language
        // Dili kontrol et
        $language = Language::find($id);

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];
        
        // Get search term
        // Arama terimini al
        $searchTerm = $request->input('search');
        $perPage = $request->input('show', 50);

        // Search translations
        // Çevirileri ara
        $translations = Translation::where('language_id', $id)
                                 ->where(function($query) use ($searchTerm) {
                                     $query->where('key', 'like', '%' . $searchTerm . '%')
                                           ->orWhere('value', 'like', '%' . $searchTerm . '%');
                                 })
                                 ->orderBy('key')
                                 ->paginate($perPage);

        // Adjust pagination for search term and show count
        // Arama terimi ve gösterme sayısı için pagination'ı düzenle
        $translations->appends(['search' => $searchTerm, 'show' => $perPage]);

        return view('content.admin.settings.language.translations', [
            'pageConfigs' => $pageConfigs,
            'language' => $language,
            'translations' => $translations,
            'searchTerm' => $searchTerm,
            'perPage' => $perPage,
            'activeMenu' => 'admin.settings.languages'
        ]);
    }
    
    /**
     * Add a new translation
     * Yeni bir çeviri ekler
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addTranslation(Request $request, $id)
    {
        // Validate request
        // İsteği doğrula
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'group' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check language
        // Dili kontrol et
        $language = Language::find($id);
        if (!$language) {
            return response()->json([
                'success' => false,
                'error' => 'Dil bulunamadı'
            ], 404);
        }

        // Check if key already exists
        // Anahtar zaten var mı kontrol et
        $existingTranslation = Translation::where('language_id', $id)
                                        ->where('key', $request->input('key'))
                                        ->first();

        if ($existingTranslation) {
            return response()->json([
                'success' => false,
                'error' => 'Bu çeviri anahtarı zaten mevcut'
            ], 422);
        }

        try {
            // Create new translation
            // Yeni çeviri oluştur
            $translation = Translation::create([
                'language_id' => $id,
                'key' => $request->input('key'),
                'value' => $request->input('value'),
                'group' => $request->input('group', 'default'),
            ]);

            // Clear translation cache
            // Çeviri önbelleğini temizle
            if (method_exists(app('translator'), 'resetCache')) {
                app('translator')->resetCache($language->code);
            }

            return response()->json([
                'success' => true,
                'message' => 'Çeviri başarıyla eklendi',
                'translation' => $translation
            ]);
        } catch (\Exception $e) {
            Log::error('Çeviri eklenirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Çeviri eklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a translation
     * Bir çeviriyi siler
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $translationId
     * @return \Illuminate\Http\Response
     */
    public function deleteTranslation(Request $request, $id, $translationId)
    {
        // Check language
        // Dili kontrol et
        $language = Language::find($id);
        if (!$language) {
            return response()->json([
                'success' => false,
                'error' => 'Dil bulunamadı'
            ], 404);
        }

        // Find translation
        // Çeviriyi bul
        $translation = Translation::where('language_id', $id)
                                ->where('id', $translationId)
                                ->first();

        if (!$translation) {
            return response()->json([
                'success' => false,
                'error' => 'Çeviri bulunamadı'
            ], 404);
        }

        try {
            // Delete translation
            // Çeviriyi sil
            $translation->delete();

            // Clear translation cache
            // Çeviri önbelleğini temizle
            if (method_exists(app('translator'), 'resetCache')) {
                app('translator')->resetCache($language->code);
            }

            return response()->json([
                'success' => true,
                'message' => 'Çeviri başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            Log::error('Çeviri silinirken hata: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Çeviri silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
