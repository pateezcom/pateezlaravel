<?php

namespace App\Translation;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\Translator;

class DatabaseTranslator extends Translator
{
    /**
     * Get the translation for the given key from database.
     * Veritabanından verilen anahtar için çeviriyi alır.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?: $this->locale;

        // Çevirileri önbellekten veya veritabanından al
        $translations = $this->getTranslationsFromDatabase($locale);

        // Önce veritabanı çevirilerini kontrol et
        if (isset($translations[$key])) {
            return $this->makeReplacements($translations[$key], $replace);
        }

        // Eğer veritabanında yoksa, orijinal Laravel çeviri sistemine yönlendir
        return parent::get($key, $replace, $locale, $fallback);
    }

    /**
     * Get translations from database by locale.
     * Yerel ayara göre veritabanından çevirileri alır.
     *
     * @param  string  $locale
     * @return array
     */
    private function getTranslationsFromDatabase($locale)
    {
        // Önbelleği zorla sıfırla eğer translations_updated bayrağı varsa
        if (Cache::has('translations_updated')) {
            Cache::forget('translations.'.$locale);
            Cache::forget('translations_updated');
        }
        
        // Önbellekte çevirileri ara, yoksa veritabanından çek
        return Cache::remember('translations.'.$locale, 60 * 24, function () use ($locale) {
            // Aktif dil ID'sini bul
            $language = DB::table('languages')
                ->where('code', $locale)
                ->where('is_active', 1)
                ->first();

            if (!$language) {
                // Dil bulunamazsa, varsayılan dili kullan
                $language = DB::table('languages')
                    ->where('is_default', 1)
                    ->first();
                
                // Hala bulunamazsa boş dizi döndür
                if (!$language) {
                    return [];
                }
            }

            // Dil ID'si ile çevirileri al
            $translations = DB::table('translations')
                ->where('language_id', $language->id)
                ->get()
                ->pluck('value', 'key')
                ->toArray();

            return $translations;
        });
    }

    /**
     * Reset the translations cache.
     * Çeviri önbelleğini sıfırlar.
     *
     * @param  string|null  $locale
     * @return void
     */
    public function resetCache($locale = null)
    {
        if ($locale) {
            Cache::forget('translations.'.$locale);
        } else {
            // Tüm diller için önbelleği sıfırla
            $languages = DB::table('languages')->get();
            foreach ($languages as $language) {
                Cache::forget('translations.'.$language->code);
            }
        }
        
        // Bir bayrak belirle, böylece sayfa yenilendiğinde önbellek yenilenecek
        Cache::put('translations_updated', true, 60 * 24);
    }
}
