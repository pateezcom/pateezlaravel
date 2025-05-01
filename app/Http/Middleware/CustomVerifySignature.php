<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class CustomVerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $relative
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next, $relative = null)
    {
        try {
            $expires = Arr::get($request->route()->parameters(), 'expires') ?? null;

            if ($expires && Carbon::createFromTimestamp($expires)->isPast()) {
                // URL'nin süresi dolmuş, kullanıcıyı bilgilendirme sayfasına yönlendir
                return redirect()->route('frontend.home')
                    ->with('error_message', 'Bu doğrulama bağlantısının süresi dolmuş. Lütfen yeni bir doğrulama e-postası isteyin.');
            }

            if (! $request->hasValidSignature($relative !== 'relative')) {
                // Geçersiz imza, kullanıcıyı bilgilendirme sayfasına yönlendir
                return redirect()->route('frontend.home')
                    ->with('error_message', 'Geçersiz doğrulama bağlantısı. Lütfen yeni bir doğrulama e-postası isteyin.');
            }

            return $next($request);
        } catch (\Exception $e) {
            // Hata oluştuğunda kullanıcıyı bilgilendirme sayfasına yönlendir
            return redirect()->route('frontend.home')
                ->with('error_message', 'Doğrulama işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }
}
