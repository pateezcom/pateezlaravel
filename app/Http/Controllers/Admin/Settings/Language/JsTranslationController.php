<?php

namespace App\Http\Controllers\Admin\Settings\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Settings\Language\Language;
use App\Models\Admin\Settings\Language\Translation;

/**
 * JS Translation Controller
 * JS Çeviri Kontrolcüsü
 * 
 * This controller handles JavaScript translations.
 * Bu kontrolcü JavaScript çevirilerini yönetir.
 */
class JsTranslationController extends Controller
{
  /**
   * Refresh translations cache
   * Çeviri önbelleğini yenile
   *
   * @return \Illuminate\Http\Response
   */
  public function refreshCache()
  {
    // Clear cache for all languages
    // Tüm diller için önbelleği temizle
    $languages = Language::all();
    foreach ($languages as $language) {
      Cache::forget('js_translations.' . $language->code);
      Cache::forget('translations.' . $language->code);
    }

    // Set cache refresh flag
    // Önbellek yenileme bayrağını ayarla
    Cache::put('translations_updated', true, 60 * 24);

    return redirect()->back()->with('success', 'Çeviri önbelleği başarıyla temizlendi.');
  }

  /**
   * Get translations for JavaScript
   * JavaScript için çevirileri al
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function getTranslationsForJs(Request $request)
  {
    $locale = App::getLocale();

    // Get translations from cache, or fetch from database
    // Önbellekten çevirileri al, yoksa veritabanından çek
    $translations = Cache::remember('js_translations.' . $locale, 60 * 24, function () use ($locale) {
      // Find active language ID by locale
      // Aktif dil ID'sini locale ile bul
      $language = Language::where('code', $locale)
                        ->where('is_active', 1)
                        ->first();

      if (!$language) {
        // If language not found, use default language
        // Dil bulunamazsa, varsayılan dili kullan
        $language = Language::where('is_default', 1)->first();

        // If still not found, return empty array
        // Hala bulunamazsa boş dizi döndür
        if (!$language) {
          return [];
        }
      }

      // Get translations by language ID
      // Dil ID'si ile çevirileri al
      $translations = Translation::where('language_id', $language->id)
                              ->get()
                              ->pluck('value', 'key')
                              ->toArray();

      return $translations;
    });

    // Return as JavaScript variable
    // JavaScript değişkeni olarak döndür
    $jsContent = "window.translations = " . json_encode($translations) . ";\n";
    $jsContent .= "window.translationsLoaded = true;\n";
    
    // Trigger event
    // Event tetikle
    $jsContent .= "if (typeof window.dispatchEvent === 'function' && typeof CustomEvent === 'function') {\n";
    $jsContent .= "  try {\n";
    $jsContent .= "    window.dispatchEvent(new CustomEvent('translationsLoaded'));\n";
    $jsContent .= "  } catch(e) {}\n";
    $jsContent .= "}\n";
    
    // Call loadTranslations function if exists
    // loadTranslations fonksiyonu varsa çağır
    $jsContent .= "if (typeof window.loadTranslations === 'function') {\n";
    $jsContent .= "  try {\n";
    $jsContent .= "    window.loadTranslations(window.translations);\n";
    $jsContent .= "  } catch(e) {}\n";
    $jsContent .= "}\n";
    
    // Refresh language table if exists
    // Dil tablosunu yenile (varsa)
    $jsContent .= "if (typeof window.refreshLanguageTable === 'function') {\n";
    $jsContent .= "  setTimeout(function() { window.refreshLanguageTable(); }, 0);\n";
    $jsContent .= "}\n";

    return response($jsContent)
      ->header('Content-Type', 'application/javascript')
      ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
  }
}
