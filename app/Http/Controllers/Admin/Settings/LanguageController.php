<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    /**
     * Display the language settings page
     * Dil ayarları sayfasını görüntüler
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];

        // AJAX isteği ise JSON olarak dön
        if ($request->ajax()) {
            $languages = DB::table('languages')->get();
            return response()->json(['data' => $languages]);
        }

        // Normal sayfa yükleme
        return view('content.admin.settings.languages', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    /**
     * Store a newly created language in database
     * Yeni bir dili veritabanına kaydeder
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'languageName' => 'required|string|max:255',
            'shortForm' => 'required|string|max:5',
            'languageCode' => 'required|string|max:10',
            'orderInput' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Yazı yönünü belirle
        $textDirection = $request->has('textDirection') ? $request->input('textDirection') : 'ltr';
        $isRTL = $textDirection === 'rtl' ? 1 : 0;
        
        // Durumu belirle
        $status = $request->has('status') ? $request->input('status') : 'active';
        $isActive = $status === 'active' || $status === 'statusActive' ? 1 : 0;

        try {
            // Dili veritabanına ekle
            $languageId = DB::table('languages')->insertGetId([
                'name' => $request->input('languageName'),
                'code' => $request->input('shortForm'),
                'icon' => 'flag-icon-' . strtolower($request->input('shortForm')),
                'is_rtl' => $isRTL,
                'is_default' => 0, // Yeni dil varsayılan değil
                'is_active' => $isActive,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Başarılı cevap döndür
            return response()->json([
                'success' => true,
                'message' => 'Dil başarıyla eklendi',
                'language_id' => $languageId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Dil eklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get language details for editing
     * Düzenleme için dil detaylarını getirir
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $language = DB::table('languages')->where('id', $id)->first();
        
        if (!$language) {
            return response()->json(['error' => 'Dil bulunamadı'], 404);
        }
        
        return response()->json(['language' => $language]);
    }

    /**
     * Update the specified language in storage
     * Belirtilen dili günceller
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'editLanguageName' => 'required|string|max:255',
            'editShortForm' => 'required|string|max:5',
            'editLanguageCode' => 'required|string|max:10',
            'editOrderInput' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Düzenlenecek dili kontrol et
        $currentLanguage = DB::table('languages')->where('id', $id)->first();
        
        if (!$currentLanguage) {
            return response()->json([
                'success' => false,
                'error' => 'Düzenlenecek dil bulunamadı'
            ], 404);
        }

        // Yazı yönünü belirle
        $textDirection = $request->has('editTextDirection') ? $request->input('editTextDirection') : 'ltr';
        $isRTL = $textDirection === 'rtl' ? 1 : 0;
        
        // Durumu belirle
        $status = $request->has('editStatus') ? $request->input('editStatus') : 'active';
        $isActive = $status === 'active' || $status === 'statusActive' ? 1 : 0;

        try {
            // Dili güncelle
            DB::table('languages')
                ->where('id', $id)
                ->update([
                    'name' => $request->input('editLanguageName'),
                    'code' => $request->input('editShortForm'),
                    'icon' => 'flag-icon-' . strtolower($request->input('editShortForm')),
                    'is_rtl' => $isRTL,
                    'is_active' => $isActive,
                    'updated_at' => now()
                ]);

            // Başarılı cevap döndür
            return response()->json([
                'success' => true,
                'message' => 'Dil başarıyla güncellendi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Dil güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified language from storage
     * Belirtilen dili siler
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Önce varsayılan dili kontrol et
        $language = DB::table('languages')->where('id', $id)->first();
        
        if (!$language) {
            return response()->json(['error' => 'Dil bulunamadı'], 404);
        }
        
        // Varsayılan dil silinemez
        if ($language->is_default == 1) {
            return response()->json(['error' => 'Varsayılan dil silinemez'], 400);
        }
        
        // Dili sil
        DB::table('languages')->where('id', $id)->delete();
        
        // Bu dile ait çevirileri de sil
        DB::table('translations')->where('language_id', $id)->delete();
        
        // Başarılı cevap döndür
        return response()->json([
            'success' => true,
            'message' => 'Dil ve ilgili çeviriler başarıyla silindi'
        ]);
    }

    /**
     * Set a language as default
     * Bir dili varsayılan olarak ayarlar
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setDefault($id)
    {
        // Önce tüm dilleri varsayılan olmayan olarak işaretle
        DB::table('languages')
            ->update(['is_default' => 0]);
        
        // Seçilen dili varsayılan olarak işaretle
        DB::table('languages')
            ->where('id', $id)
            ->update([
                'is_default' => 1,
                'is_active' => 1, // Varsayılan dil her zaman aktif olmalı
                'updated_at' => now()
            ]);
        
        // Başarılı cevap döndür
        return response()->json([
            'success' => true,
            'message' => 'Varsayılan dil başarıyla güncellendi'
        ]);
    }

    /**
     * Import language file
     * Dil dosyasını içe aktarır
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // Dosya yükleme kontrolü
        if (!$request->hasFile('languageFile')) {
            return response()->json(['error' => 'Dosya bulunamadı'], 400);
        }
        
        $file = $request->file('languageFile');
        
        // JSON dosyası mı kontrol et
        if ($file->getClientOriginalExtension() !== 'json') {
            return response()->json(['error' => 'Dosya JSON formatında olmalıdır'], 400);
        }
        
        try {
            // JSON dosyasını oku
            $content = file_get_contents($file->getRealPath());
            $languageData = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Geçersiz JSON formatı'], 400);
            }
            
            // Dil bilgilerini kontrol et
            if (!isset($languageData['language']) || !isset($languageData['translations'])) {
                return response()->json(['error' => 'Geçersiz dil dosyası formatı'], 400);
            }
            
            DB::beginTransaction();
            try {
                // Dil kaydı ekle
                $language = $languageData['language'];
                $shortForm = $language['short_form'];
                
                // Dil zaten var mı kontrol et
                $existingLanguage = DB::table('languages')
                    ->where('code', $shortForm)
                    ->first();
                
                if ($existingLanguage) {
                    // Dil zaten varsa güncelle
                    $languageId = $existingLanguage->id;
                    
                    DB::table('languages')
                        ->where('id', $languageId)
                        ->update([
                            'name' => $language['name'],
                            'is_rtl' => $language['text_direction'] === 'rtl' ? 1 : 0,
                            'updated_at' => now()
                        ]);
                    
                    // Eski çevirileri sil
                    DB::table('translations')
                        ->where('language_id', $languageId)
                        ->delete();
                } else {
                    // Yeni dil ekle
                    $languageId = DB::table('languages')->insertGetId([
                        'name' => $language['name'],
                        'code' => $shortForm,
                        'icon' => 'flag-icon-' . strtolower($shortForm),
                        'is_rtl' => $language['text_direction'] === 'rtl' ? 1 : 0,
                        'is_default' => 0,
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                
                // Çevirileri ekle
                $translations = $languageData['translations'];
                $translationData = [];
                
                foreach ($translations as $translation) {
                    $translationData[] = [
                        'language_id' => $languageId,
                        'group' => 'default',
                        'key' => $translation['label'],
                        'value' => $translation['translation'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                
                // Çevirileri toplu ekle
                DB::table('translations')->insert($translationData);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Dil başarıyla içe aktarıldı'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Dosya işlenirken bir hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export language translations to JSON file
     * Dil çevirilerini JSON dosyasına aktarır
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export($id)
    {
        // Dili kontrol et
        $language = DB::table('languages')->where('id', $id)->first();
        
        if (!$language) {
            return response()->json(['error' => 'Dil bulunamadı'], 404);
        }
        
        // Dile ait çevirileri getir
        $translations = DB::table('translations')
            ->where('language_id', $id)
            ->get();
        
        // JSON formatı için veriyi hazırla
        $exportData = [
            'language' => [
                'name' => $language->name,
                'short_form' => $language->code,
                'language_code' => $language->code . '-' . strtoupper($language->code),
                'text_direction' => $language->is_rtl ? 'rtl' : 'ltr',
                'text_editor_lang' => $language->code
            ],
            'translations' => []
        ];
        
        foreach ($translations as $translation) {
            $exportData['translations'][] = [
                'label' => $translation->key,
                'translation' => $translation->value
            ];
        }
        
        // JSON formatına dönüştür
        $jsonContent = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Dosyayı indirme başlıklarını ayarla
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $language->name . '.json"',
        ];
        
        // JSON içeriğini döndür
        return response($jsonContent, 200, $headers);
    }
}
