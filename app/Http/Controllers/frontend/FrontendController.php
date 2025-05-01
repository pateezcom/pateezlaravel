<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        
        // E-posta doğrulama başarılı mesajı
        if ($request->session()->has('verified')) {
            $data['verified'] = true;
        }
        
        // E-posta doğrulama hata mesajı
        if ($request->session()->has('error_message')) {
            $data['error_message'] = $request->session()->get('error_message');
        }
        
        return view('frontend.index', $data);
    }
}
