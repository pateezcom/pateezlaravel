<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    /**
     * Önbelleği temizleme ve çevirileri yenileme
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshCache()
    {
        // Çevirilerin güncellendiğini belirten değeri temizle
        Cache::put('translations_updated', true, now()->addDay());
        
        // Tüm dillerin çeviri önbelleklerini temizle
        $languages = DB::table('languages')->get();
        foreach ($languages as $language) {
            Cache::forget("translations.{$language->code}");
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Çeviri önbelleği başarıyla temizlendi.'
        ]);
    }

    /**
     * Mevcut aktif dili ayarlama
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale(Request $request, $locale)
    {
        // Dil geçerliliğini kontrol et
        $language = DB::table('languages')
            ->where('code', $locale)
            ->where('is_active', 1)
            ->first();
        
        if (!$language) {
            return redirect()->back();
        }

        // Oturuma dili kaydet
        session(['locale' => $locale]);
        
        // Cookie ayarla (1 yıl süreli)
        return redirect()->back()
            ->cookie('locale', $locale, 60 * 24 * 365);
    }
}
