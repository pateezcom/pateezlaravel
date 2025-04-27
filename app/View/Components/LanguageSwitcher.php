<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class LanguageSwitcher extends Component
{
    /**
     * Kullanılabilir diller
     *
     * @var array
     */
    public $languages;

    /**
     * Mevcut dil
     *
     * @var string
     */
    public $currentLocale;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Tüm aktif dilleri al
        $this->languages = DB::table('languages')
            ->where('is_active', 1)
            ->get();
            
        // Mevcut dili al
        $this->currentLocale = app()->getLocale();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.language-switcher');
    }
}
