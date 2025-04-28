<?php

namespace App\Http\Controllers\Admin\Settings\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Settings\Language\Language;
use App\Models\Admin\Settings\Language\Translation;

/**
 * Language Controller
 * Dil Kontrolcüsü
 * 
 * This controller handles language management operations.
 * Bu kontrolcü dil yönetimi işlemlerini yönetir.
 */
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

    // If AJAX request, return JSON
    // AJAX isteği ise JSON olarak dön
    if ($request->ajax()) {
      $languages = Language::all();
      return response()->json(['data' => $languages]);
    }

    // Normal page load
    // Normal sayfa yükleme
    return view('content.admin.settings.language.languages', [
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
    // Form verilerini doğrula
    $validator = Validator::make($request->all(), [
      'languageName' => [
        'required',
        'string',
        'max:255',
        function ($attribute, $value, $fail) {
          // Language name must be unique
          // Dil adı benzersiz olmalı
          $exists = Language::where('name', $value)->exists();
          if ($exists) {
            $fail('Bu dil adı zaten kayıtlı');
          }
        },
      ],
      'shortForm' => [
        'required',
        'string',
        'max:5',
        function ($attribute, $value, $fail) {
          // Short form must be unique
          // Kısa form benzersiz olmalı
          $exists = Language::where('code', $value)->exists();
          if ($exists) {
            $fail('Bu kısa form zaten kayıtlı');
          }
        },
      ],
      'languageCode' => [
        'required',
        'string',
        'max:10',
        function ($attribute, $value, $fail) {
          // Language code must be unique
          // Dil kodu benzersiz olmalı
          $exists = Language::where('code', $value)->exists();
          if ($exists) {
            $fail('Bu dil kodu zaten kayıtlı');
          }
        },
      ],
      'orderInput' => 'required|integer'
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // Determine text direction
    // Yazı yönünü belirle
    $textDirection = $request->has('textDirection') ? $request->input('textDirection') : 'ltr';
    $isRTL = $textDirection === 'rtl' ? 1 : 0;

    // Determine status
    // Durumu belirle
    $status = $request->has('status') ? $request->input('status') : 'active';
    $isActive = $status === 'active' || $status === 'statusActive' ? 1 : 0;

    try {
      // Add language to database
      // Dili veritabanına ekle
      $language = Language::create([
        'name' => $request->input('languageName'),
        'code' => $request->input('shortForm'),
        'icon' => 'flag-icon-' . strtolower($request->input('shortForm')),
        'text_editor_lang' => $request->input('textEditorLanguage'),
        'is_rtl' => $isRTL,
        'is_default' => 0, // New language is not default
        'is_active' => $isActive,
      ]);

      // Return successful response
      // Başarılı cevap döndür
      return response()->json([
        'success' => true,
        'message' => 'Dil başarıyla eklendi',
        'language_id' => $language->id
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
    $language = Language::find($id);

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
    // Form verilerini doğrula
    $validator = Validator::make($request->all(), [
      'editLanguageName' => [
        'required',
        'string',
        'max:255',
        function ($attribute, $value, $fail) use ($id) {
          // Language name must be unique (except itself)
          // Dil adı benzersiz olmalı (kendi dışında)
          $exists = Language::where('name', $value)
            ->where('id', '!=', $id)
            ->exists();
          if ($exists) {
            $fail('Bu dil adı zaten kayıtlı');
          }
        },
      ],
      'editShortForm' => [
        'required',
        'string',
        'max:5',
        function ($attribute, $value, $fail) use ($id) {
          // Short form must be unique (except itself)
          // Kısa form benzersiz olmalı (kendi dışında)
          $exists = Language::where('code', $value)
            ->where('id', '!=', $id)
            ->exists();
          if ($exists) {
            $fail('Bu kısa form zaten kayıtlı');
          }
        },
      ],
      'editLanguageCode' => [
        'required',
        'string',
        'max:10',
        function ($attribute, $value, $fail) use ($id) {
          // Language code must be unique (except itself)
          // Dil kodu benzersiz olmalı (kendi dışında)
          $exists = Language::where('code', $value)
            ->where('id', '!=', $id)
            ->exists();
          if ($exists) {
            $fail('Bu dil kodu zaten kayıtlı');
          }
        },
      ],
      'editOrderInput' => 'required|integer'
    ]);

    if ($validator->fails()) {
      return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // Check language to edit
    // Düzenlenecek dili kontrol et
    $language = Language::find($id);

    if (!$language) {
      return response()->json([
        'success' => false,
        'error' => 'Düzenlenecek dil bulunamadı'
      ], 404);
    }

    // Determine text direction
    // Yazı yönünü belirle
    $textDirection = $request->has('editTextDirection') ? $request->input('editTextDirection') : 'ltr';
    $isRTL = $textDirection === 'rtl' ? 1 : 0;

    // Determine status
    // Durumu belirle
    $status = $request->has('editStatus') ? $request->input('editStatus') : 'active';
    $isActive = $status === 'active' || $status === 'statusActive' ? 1 : 0;

    try {
      // Update language
      // Dili güncelle
      $language->name = $request->input('editLanguageName');
      $language->code = $request->input('editShortForm');
      $language->icon = 'flag-icon-' . strtolower($request->input('editShortForm'));
      $language->text_editor_lang = $request->input('editTextEditorLanguage');
      $language->is_rtl = $isRTL;
      $language->is_active = $isActive;
      $language->save();

      // Return successful response
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
    // First check if default language
    // Önce varsayılan dili kontrol et
    $language = Language::find($id);

    if (!$language) {
      return response()->json(['error' => 'Dil bulunamadı'], 404);
    }

    // Default language cannot be deleted
    // Varsayılan dil silinemez
    if ($language->is_default == 1) {
      return response()->json(['error' => 'Varsayılan dil silinemez'], 400);
    }

    try {
      // Delete related translations
      // İlgili çevirileri sil
      Translation::where('language_id', $id)->delete();
      
      // Delete language
      // Dili sil
      $language->delete();

      // Return successful response
      // Başarılı cevap döndür
      return response()->json([
        'success' => true,
        'message' => 'Dil ve ilgili çeviriler başarıyla silindi'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Dil silinirken bir hata oluştu: ' . $e->getMessage()
      ], 500);
    }
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
    try {
      // First unset all languages as default
      // Önce tüm dilleri varsayılan olmayan olarak işaretle
      Language::where('is_default', 1)->update(['is_default' => 0]);

      // Set selected language as default
      // Seçilen dili varsayılan olarak işaretle
      $language = Language::findOrFail($id);
      $language->is_default = 1;
      $language->is_active = 1; // Default language must always be active
      $language->save();

      // Return successful response
      // Başarılı cevap döndür
      return response()->json([
        'success' => true,
        'message' => 'Varsayılan dil başarıyla güncellendi'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Varsayılan dil ayarlanırken bir hata oluştu: ' . $e->getMessage()
      ], 500);
    }
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
    // Check if AJAX request
    // Sadece AJAX isteği mi kontrol et
    if (!$request->ajax()) {
      return response()->json(['error' => 'Sadece AJAX istekleri kabul edilir'], 400);
    }
    
    // Check file upload
    // Dosya yükleme kontrolü
    if (!$request->hasFile('languageFile')) {
      return response()->json(['error' => 'Dosya bulunamadı'], 400);
    }

    $file = $request->file('languageFile');

    // Check if JSON file
    // JSON dosyası mı kontrol et
    if ($file->getClientOriginalExtension() !== 'json') {
      return response()->json(['error' => 'Dosya JSON formatında olmalıdır'], 400);
    }

    try {
      // Read JSON file
      // JSON dosyasını oku
      $content = file_get_contents($file->getRealPath());
      $languageData = json_decode($content, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        return response()->json(['error' => 'Geçersiz JSON formatı'], 400);
      }

      // Check language info
      // Dil bilgilerini kontrol et
      if (!isset($languageData['language']) || !isset($languageData['translations'])) {
        return response()->json(['error' => 'Geçersiz dil dosyası formatı'], 400);
      }

      // Check language info
      // Dil bilgilerini kontrol et
      $language = $languageData['language'];
      
      // Check required fields
      // Gerekli alanların varlığını kontrol et
      if (!isset($language['name']) || !isset($language['short_form'])) {
        return response()->json(['error' => 'Dosyada dil adı veya kısa formu eksik'], 400);
      }

      // Check translations format
      // Çevirilerin formatını kontrol et
      $translations = $languageData['translations'];
      if (!is_array($translations) || empty($translations)) {
        return response()->json(['error' => 'Çeviri listesi boş veya geçersiz'], 400);
      }
      
      // Check translation format
      // Çeviri formatını kontrol et
      foreach ($translations as $translation) {
        if (!isset($translation['label']) || !isset($translation['translation'])) {
          return response()->json(['error' => 'Bazı çevirilerde label veya translation alanı eksik'], 400);
        }
      }

      DB::beginTransaction();
      try {
        // Get language info
        // Dil bilgilerini al
        $languageName = $language['name'];
        $shortForm = $language['short_form'];
        $languageCode = $language['language_code'] ?? $shortForm;
        $textDirection = $language['text_direction'] ?? 'ltr';
        $textEditorLang = $language['text_editor_lang'] ?? $shortForm;

        // Step 1: Check language name
        // 1. Adım: Dil adını kontrol et
        $existingLanguageByName = Language::where('name', $languageName)->first();

        if ($existingLanguageByName) {
          return response()->json([
            'success' => false,
            'message' => 'Bu dil adı (' . $languageName . ') zaten mevcut'
          ], 400);
        }
        
        // Step 2: Check short form (code)
        // 2. Adım: Kısa form (code) kontrol et
        $existingLanguageByCode = Language::where('code', $shortForm)->first();

        if ($existingLanguageByCode) {
          return response()->json([
            'success' => false,
            'message' => 'Bu dil kodu (' . $shortForm . ') zaten mevcut'
          ], 400);
        }
        
        // Step 3: Add new language
        // 3. Adım: Yeni dil ekle
        $newLanguage = Language::create([
          'name' => $languageName,
          'code' => $shortForm,
          'icon' => 'flag-icon-' . strtolower($shortForm),
          'text_editor_lang' => $textEditorLang,
          'is_rtl' => $textDirection === 'rtl' ? 1 : 0,
          'is_default' => 0,
          'is_active' => 1,
        ]);

        // Step 4: Add translations
        // 4. Adım: Çevirileri ekle
        $translationBatch = [];
        $processedCount = 0;
        
        foreach ($translations as $translation) {
          Translation::create([
            'language_id' => $newLanguage->id,
            'group' => 'default',
            'key' => $translation['label'],
            'value' => $translation['translation'],
          ]);
          $processedCount++;
        }

        DB::commit();
        return response()->json([
          'success' => true,
          'message' => $languageName . ' dili ve ' . $processedCount . ' çeviri başarıyla içe aktarıldı'
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
    // Check language
    // Dili kontrol et
    $language = Language::find($id);

    if (!$language) {
      return response()->json(['error' => 'Dil bulunamadı'], 404);
    }

    // Get translations for language
    // Dile ait çevirileri getir
    $translations = Translation::where('language_id', $id)->get();

    // Prepare data for JSON format
    // JSON formatı için veriyi hazırla
    $exportData = [
      'language' => [
        'name' => $language->name,
        'short_form' => $language->code,
        'language_code' => $language->code . '-' . strtoupper($language->code),
        'text_direction' => $language->is_rtl ? 'rtl' : 'ltr',
        'text_editor_lang' => $language->text_editor_lang ?? $language->code
      ],
      'translations' => []
    ];

    foreach ($translations as $translation) {
      $exportData['translations'][] = [
        'label' => $translation->key,
        'translation' => $translation->value
      ];
    }

    // Convert to JSON format
    // JSON formatına dönüştür
    $jsonContent = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Set download headers
    // Dosyayı indirme başlıklarını ayarla
    $headers = [
      'Content-Type' => 'application/json',
      'Content-Disposition' => 'attachment; filename="' . $language->name . '.json"',
    ];

    // Return JSON content
    // JSON içeriğini döndür
    return response($jsonContent, 200, $headers);
  }

  /**
   * Check if a field value is unique in languages table
   * Dil alanları için benzersizlik kontrolü yapar
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function checkUnique(Request $request)
  {
    $field = $request->input('field');
    $value = $request->input('value');
    $excludeId = $request->input('exclude_id');

    // Which field to check?
    // Hangi alanı kontrol edeceğiz?
    $dbField = '';
    switch ($field) {
      case 'languageName':
      case 'editLanguageName':
        $dbField = 'name';
        break;
      case 'shortForm':
      case 'editShortForm':
      case 'languageCode':
      case 'editLanguageCode':
        $dbField = 'code';
        break;
      default:
        return response()->json(['unique' => true], 200);
    }

    // Prepare query for existing record
    // Var olan kayıt için sorgu hazırla
    $query = Language::where($dbField, $value);

    // If exclude_id is specified, exclude this ID from check
    // Eğer exclude_id belirtilmişse, bu ID'yi kontrol dışı bırak
    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }

    // Check if record exists
    // Kayıt var mı kontrol et
    $exists = $query->exists();

    // Return result - if record doesn't exist, it's unique
    // Sonuç dön - eğer kayıt yoksa unique'dir
    return response()->json(['unique' => !$exists], 200);
  }
}
