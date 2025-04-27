<?php

namespace App\Translation;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class DatabaseTranslationLoader implements Loader
{
    /**
     * Laravel'in varsayılan FileLoader'ı
     *
     * @var \Illuminate\Contracts\Translation\Loader
     */
    protected $fileLoader;

    /**
     * Cache yaşam süresi (dakika)
     *
     * @var int
     */
    protected $cacheTtl = 60;

    /**
     * Create a new database translation loader instance.
     *
     * @param  \Illuminate\Contracts\Translation\Loader  $fileLoader
     * @return void
     */
    public function __construct(Loader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        // Eğer namespace '*' değilse ve grup '*' değilse, dosya yükleyiciyi kullan
        if ($namespace !== '*' && $namespace !== null) {
            return $this->fileLoader->load($locale, $group, $namespace);
        }

        // Önce dosyadan çevirileri yükle
        $lines = $this->fileLoader->load($locale, $group, $namespace);
        
        // Eğer dosyadan gelen çeviriler zaten gruplandırılmış durumda ise doğrudan döndür
        if ($group !== 'default') {
            return $lines;
        }

        // Veritabanından çevirileri al
        $dbLines = $this->loadFromDatabase($locale);

        // Eğer veritabanı çevirileri varsa, dosyadan gelen çevirileri bunlarla birleştir
        // Veritabanı çevirileri öncelikli olacak şekilde
        return array_merge($lines, $dbLines);
    }

    /**
     * Veritabanından çevirileri yükle.
     *
     * @param  string  $locale
     * @return array
     */
    protected function loadFromDatabase($locale)
    {
        // Önbelleği kontrol et
        $cacheKey = "translations.{$locale}";
        
        // Çevirilerin güncellendiğini kontrol et
        $translationsUpdated = Cache::get('translations_updated', false);
        
        if (!$translationsUpdated && Cache::has($cacheKey)) {
            return Cache::get($cacheKey, []);
        }

        // Veritabanında ilgili dil var mı kontrol et
        $language = DB::table('languages')
            ->where('code', $locale)
            ->first();

        if (!$language) {
            return [];
        }

        // Veritabanından çevirileri al
        $translations = DB::table('translations')
            ->where('language_id', $language->id)
            ->get();

        // Çevirileri düzenle
        $lines = [];
        foreach ($translations as $translation) {
            $lines[$translation->key] = $translation->value;
        }

        // Önbelleğe al
        Cache::put($cacheKey, $lines, now()->addMinutes($this->cacheTtl));
        
        // Çevirilerin güncellenme durumunu önbelleğe kaydet
        Cache::put('translations_updated', false, now()->addDay());

        return $lines;
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->fileLoader->addNamespace($namespace, $hint);
    }

    /**
     * Add a new JSON path to the loader.
     *
     * @param  string  $path
     * @return void
     */
    public function addJsonPath($path)
    {
        $this->fileLoader->addJsonPath($path);
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return $this->fileLoader->namespaces();
    }
}
