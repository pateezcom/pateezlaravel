<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Display the language settings page
     * Dil ayarları sayfasını görüntüler
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageConfigs = ['layoutType' => 'content-detached-right-sidebar'];
        $breadcrumbs = [
            ['link' => 'home', 'name' => 'Ana Sayfa'],
            ['link' => 'admin/dashboard', 'name' => 'Dashboard'],
            ['name' => 'Dil Ayarları'],
        ];

        // Daha sonra veritabanından dil bilgilerini çekeceğiz.
        // Şimdilik örnek veri oluşturalım
        $languages = [
            [
                'id' => 1,
                'name' => 'English',
                'code' => 'en',
                'short_form' => 'en',
                'is_default' => true,
                'is_active' => true
            ],
            [
                'id' => 2,
                'name' => 'Arabic',
                'code' => 'ar',
                'short_form' => 'ar',
                'is_default' => false,
                'is_active' => true
            ]
        ];

        return view('content.admin.settings.languages', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            'languages' => $languages
        ]);
    }
}
