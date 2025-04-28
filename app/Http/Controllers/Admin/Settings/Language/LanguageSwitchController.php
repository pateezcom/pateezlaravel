<?php

namespace App\Http\Controllers\Admin\Settings\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\Settings\Language\Language;

/**
 * Language Switch Controller
 * Dil Değiştirme Kontrolcüsü
 * 
 * This controller handles language switching operations.
 * Bu kontrolcü dil değiştirme işlemlerini yönetir.
 */
class LanguageSwitchController extends Controller
{
    /**
     * Switch the application language
     * Uygulama dilini değiştirir
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang(Request $request, $locale)
    {
        // Check if language is active
        // Dilin aktif olup olmadığını kontrol et
        $language = Language::where('code', $locale)
                          ->where('is_active', 1)
                          ->first();

        if (!$language) {
            // If language not found or not active, redirect to default language
            // Dil bulunamadıysa veya aktif değilse, varsayılan dile yönlendir
            $defaultLanguage = Language::where('is_default', 1)
                                     ->where('is_active', 1)
                                     ->first();
            
            $locale = $defaultLanguage ? $defaultLanguage->code : config('app.locale');
        }

        // Set language
        // Dili ayarla
        App::setLocale($locale);
        Session::put('locale', $locale);

        // Save language to cookie (for 1 year)
        // Dili cookie'ye kaydet (1 yıl süreyle)
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        // Redirect back to previous page
        // Önceki sayfaya yönlendir
        return redirect()->back();
    }
}
