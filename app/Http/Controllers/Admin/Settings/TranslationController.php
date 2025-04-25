<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        // Dili kontrol et
        $language = DB::table('languages')->where('id', $id)->first();

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];
        
        // Tüm çevirileri getir
        $translations = DB::table('translations')
            ->where('language_id', $id)
            ->orderBy('key')
            ->paginate(50);

        return view('content.admin.settings.translations', [
            'pageConfigs' => $pageConfigs,
            'language' => $language,
            'translations' => $translations
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
        // Dili kontrol et
        $language = DB::table('languages')->where('id', $id)->first();

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        // Gelen çevirileri al
        $translations = $request->input('translations', []);

        // Veritabanı işlemi için transaction başlat
        DB::beginTransaction();

        try {
            // Her çeviriyi güncelle
            foreach ($translations as $key => $value) {
                DB::table('translations')
                    ->where('language_id', $id)
                    ->where('key', $key)
                    ->update([
                        'value' => $value,
                        'updated_at' => now()
                    ]);
            }

            // İşlemi tamamla
            DB::commit();

            // Başarılı yanıt döndür - AJAX isteği için
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Çeviriler başarıyla güncellendi'
                ]);
            }

            // Normal form gönderimi için
            return redirect()->back()->with('success', 'Çeviriler başarıyla güncellendi');
        } catch (\Exception $e) {
            // Hata durumunda işlemi geri al
            DB::rollBack();
            Log::error('Çeviri güncellenirken hata: ' . $e->getMessage());

            // AJAX isteği için
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Çeviriler güncellenirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }

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
        // Dili kontrol et
        $language = DB::table('languages')->where('id', $id)->first();

        if (!$language) {
            return redirect()->route('admin.settings.languages')->with('error', 'Dil bulunamadı');
        }

        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];
        
        // Arama terimini al
        $searchTerm = $request->input('search');
        $perPage = $request->input('show', 50);

        // Çevirileri ara
        $translations = DB::table('translations')
            ->where('language_id', $id)
            ->where(function($query) use ($searchTerm) {
                $query->where('key', 'like', '%' . $searchTerm . '%')
                      ->orWhere('value', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('key')
            ->paginate($perPage);

        // Arama terimi ve gösterme sayısı için pagination'ı düzenle
        $translations->appends(['search' => $searchTerm, 'show' => $perPage]);

        return view('content.admin.settings.translations', [
            'pageConfigs' => $pageConfigs,
            'language' => $language,
            'translations' => $translations,
            'searchTerm' => $searchTerm,
            'perPage' => $perPage
        ]);
    }
}
