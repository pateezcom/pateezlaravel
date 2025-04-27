<?php

namespace App\Providers;

use App\Translation\DatabaseTranslator;
use Illuminate\Translation\TranslationServiceProvider as LaravelTranslationServiceProvider;

class TranslationServiceProvider extends LaravelTranslationServiceProvider
{
    /**
     * Register the service provider.
     * Servis sağlayıcıyı kaydeder.
     *
     * @return void
     */
    public function register()
    {
        // Önce standart yükleyiciyi (loader) kaydet
        $this->registerLoader();

        // Sonra kendi çevirmen (translator) sınıfımızı kaydet
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // Get the locale from the application
            $locale = $app->getLocale();

            // Create a new translator instance
            $translator = new DatabaseTranslator($loader, $locale);
            $translator->setFallback($app->getFallbackLocale());

            return $translator;
        });
    }
}
