<?php

namespace App\Http\Controllers;

use App\Translation\DatabaseTranslator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseTranslationController extends Controller
{
    /**
     * Refresh translation cache
     * Çeviri önbelleğini yeniler
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $locale
     * @return \Illuminate\Http\Response
     */
    public function refreshCache(Request $request, $locale = null)
    {
        try {
            /** @var DatabaseTranslator $translator */
            $translator = app('translator');
            $translator->resetCache($locale);
            
            return response()->json(['success' => true, 'message' => 'Çeviri önbelleği başarıyla yenilendi.']);
        } catch (\Exception $e) {
            Log::error('Çeviri önbelleği yenilenirken hata: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Çeviri önbelleği yenilenirken hata oluştu.']);
        }
    }

    /**
     * Get translations for a specific key
     * Belirli bir anahtar için çevirileri alır
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function getTranslation(Request $request, $key)
    {
        $locale = $request->input('locale', app()->getLocale());
        
        try {
            $translation = trans($key, [], $locale);
            return response()->json(['success' => true, 'translation' => $translation]);
        } catch (\Exception $e) {
            Log::error('Çeviri alınırken hata: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Çeviri alınırken hata oluştu.']);
        }
    }
}
