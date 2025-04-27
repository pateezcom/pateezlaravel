<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kullanıcı dil seçtiyse, oturumdan al
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Cookie'den dil ayarını kontrol et
        elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
            Session::put('locale', $locale);
        }
        // Varsayılan dili al
        else {
            // Veritabanından varsayılan dili al
            $language = DB::table('languages')
                ->where('is_default', 1)
                ->where('is_active', 1)
                ->first();
                
            $locale = $language ? $language->code : config('app.locale');
            Session::put('locale', $locale);
        }

        // Dili ayarla
        App::setLocale($locale);
        
        return $next($request);
    }
}
