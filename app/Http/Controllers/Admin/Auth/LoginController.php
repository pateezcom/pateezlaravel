<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Users\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.admin.admin-login', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|string',
      'password' => 'required',
    ]);

    $input = $request->email;
    $password = $request->password;

    // Kullanıcıyı e-posta veya kullanıcı adıyla ara
    $user = User::where('email', $input)
                ->orWhere('username', $input)
                ->first();

    // Kullanıcı bulundu ve şifre doğru ise
    if ($user && Hash::check($password, $user->password)) {
      Auth::login($user, false);
      $request->session()->regenerate();
      return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
      'email' => 'Girilen bilgiler kayıtlarımızla eşleşmiyor.',
    ])->withInput($request->except('password'));
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
  }
}
