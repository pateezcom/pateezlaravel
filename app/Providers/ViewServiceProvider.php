<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * Servisleri kaydeder.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * Servisleri başlatır.
     *
     * @return void
     */
    public function boot()
    {
        // Share active languages with all views
        // Tüm aktif dilleri tüm view'larla paylaş
        $this->shareActiveLanguages();
    }

    /**
     * Share active languages with all views
     * Tüm aktif dilleri tüm view'larla paylaş
     *
     * @return void
     */
    private function shareActiveLanguages()
    {
        try {
            // Check if languages table exists and has records
            // Diller tablosunun var olup olmadığını ve kayıt içerip içermediğini kontrol et
            $activeLanguages = DB::table('languages')
                ->where('is_active', 1)
                ->orderBy('is_default', 'desc')
                ->orderBy('name')
                ->get();

            View::share('activeLanguages', $activeLanguages);
        } catch (\Exception $e) {
            // If there's an error (e.g. table doesn't exist), share empty collection
            // Bir hata varsa (örn. tablo yok), boş koleksiyon paylaş
            View::share('activeLanguages', collect([]));
        }
    }
}
