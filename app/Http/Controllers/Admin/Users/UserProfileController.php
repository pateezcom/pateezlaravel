<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Admin\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Kullanıcı profilini gösterme
     * Display user profile
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            return view('content.admin.users.user-profile', [
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('User profile error: ' . $e->getMessage());
            return redirect()->route('admin.users')
                ->with('error', __('Kullanıcı profili yüklenirken bir hata oluştu.'));
        }
    }
    
    /**
     * Kullanıcı hesap bilgilerini güncelleme
     * Update user account information
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request, $id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            // Yalnızca form verileri validasyonu
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'slug' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9\-à-ÿ\s]+$/', // Türkçe karakterler ve boşluk kullanımına izin ver
                    Rule::unique('users')->ignore($user->id),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'phone' => 'nullable|string|max:20|regex:/^\+[0-9]{1,4}[0-9]{6,15}$/',
                'about_me' => 'nullable|string|max:5000',
                'facebook' => 'nullable|string|max:1000|url',
                'twitter' => 'nullable|string|max:1000|url',
                'instagram' => 'nullable|string|max:1000|url',
                'tiktok' => 'nullable|string|max:1000|url',
                'whatsapp' => 'nullable|string|max:1000|url',
                'youtube' => 'nullable|string|max:1000|url',
                'discord' => 'nullable|string|max:1000|url',
                'telegram' => 'nullable|string|max:1000|url',
                'pinterest' => 'nullable|string|max:1000|url',
                'linkedin' => 'nullable|string|max:1000|url',
                'twitch' => 'nullable|string|max:1000|url',
                'vk' => 'nullable|string|max:1000|url',
                'personal_website_url' => 'nullable|string|max:1000|url',
            ]);
            
            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Form doğrulama hatası.'),
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Slug yoksa username'den oluştur
            if (empty($request->input('slug'))) {
                // Kullanıcı adını al ve slug oluştur
                $titleStr = $request->input('username');

                // Türkçe karakterleri ve özel karakterleri düzenle
                $replacements = [
                    'ç' => 'c', 'Ç' => 'C', 'ğ' => 'g', 'Ğ' => 'G',
                    'ı' => 'i', 'İ' => 'I', 'ö' => 'o', 'Ö' => 'O',
                    'ş' => 's', 'Ş' => 'S', 'ü' => 'u', 'Ü' => 'U',
                    ' ' => '-',  # Boşlukları tire ile değiştir
                ];

                $slug = $titleStr;
                // Türkçe ve özel karakterleri değiştir
                foreach ($replacements as $find => $replace) {
                    $slug = str_replace($find, $replace, $slug);
                }

                // Sadece izin verilen karakterleri bırak (alfanümerik ve tire)
                $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
                // Birden fazla tireyi tek tire yap
                $slug = preg_replace('/-+/', '-', $slug);
                // Baştaki ve sondaki tireleri kaldır
                $slug = trim($slug, '-');
                // Küçük harfe çevir
                $slug = strtolower($slug);
                
                // Eğer slug boşsa (sadece özel karakterler içeriyorsa)
                if (empty($slug)) {
                    $slug = 'user-' . time(); // Varsayılan slug oluştur
                }
                
                // Eğer slug zaten başka bir kullanıcı tarafından kullanılıyorsa, değiştir
                $i = 1;
                $originalSlug = $slug;
                while (User::where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                    $slug = $originalSlug . '-' . $i++;
                }
            } else {
                $slug = $request->input('slug');
            }
            
            // Kullanıcı bilgilerini güncelle
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->slug = $slug;
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->about_me = $request->input('about_me');
            
            // Sosyal medya hesaplarını güncelle
            $user->facebook = $request->input('facebook');
            $user->twitter = $request->input('twitter');
            $user->instagram = $request->input('instagram');
            $user->tiktok = $request->input('tiktok');
            $user->whatsapp = $request->input('whatsapp');
            $user->youtube = $request->input('youtube');
            $user->discord = $request->input('discord');
            $user->telegram = $request->input('telegram');
            $user->pinterest = $request->input('pinterest');
            $user->linkedin = $request->input('linkedin');
            $user->twitch = $request->input('twitch');
            $user->vk = $request->input('vk');
            $user->personal_website_url = $request->input('personal_website_url');
            
            // Değişiklikleri kaydet
            $user->save();
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Hesap bilgileri başarıyla güncellendi.')
                ]);
            }
            
            // Normal istek ise sayfaya yönlendir
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Hesap bilgileri başarıyla güncellendi.'));
                
        } catch (\Exception $e) {
            Log::error('User account update error: ' . $e->getMessage());
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Hesap bilgileri güncellenirken bir hata oluştu.')
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', __('Hesap bilgileri güncellenirken bir hata oluştu.'))
                ->withInput();
        }
    }
    
    /**
     * Kullanıcı profil fotoğrafını güncelleme
     * Update user profile photo
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePhoto(Request $request, $id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            // Yalnızca form verileri validasyonu
            $validator = Validator::make($request->all(), [
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Debug bilgilerini loglama
            Log::info('Profile photo update request:', [
                'has_file' => $request->hasFile('profile_photo'),
                'file_valid' => $request->file('profile_photo') ? $request->file('profile_photo')->isValid() : false,
                'all_inputs' => $request->all()
            ]);
            
            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Form doğrulama hatası.'),
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Profil fotoğrafını güncelle (HasProfilePhoto trait'i kullanarak)
            if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
                try {
                    $user->updateProfilePhoto($request->file('profile_photo'));
                    // Başarılı olursa loglama
                    Log::info('Profile photo updated successfully for user ID: ' . $user->id);
                } catch (\Exception $e) {
                    // Hata durumunda loglama
                    Log::error('Error updating profile photo: ' . $e->getMessage());
                    throw $e;
                }
            } else {
                Log::warning('No valid profile photo found in request for user ID: ' . $user->id);
            }
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => __('Profil fotoğrafı başarıyla güncellendi.'),
                    'photo_url' => $user->profile_photo_url
                ]);
            }
            
            // Profil sayfasına başarı mesajıyla dön
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Profil fotoğrafı başarıyla güncellendi.'));
                
        } catch (\Exception $e) {
            Log::error('User profile photo update error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Profil fotoğrafı güncellenirken bir hata oluştu.')
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', __('Profil fotoğrafı güncellenirken bir hata oluştu.'))
                ->withInput();
        }
    }
    
    /**
     * Kullanıcı profil fotoğrafını silme
     * Remove user profile photo
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProfilePhoto($id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            // Profil fotoğrafını sil (HasProfilePhoto trait'i kullanarak)
            $user->deleteProfilePhoto();
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => __('Profil fotoğrafı başarıyla silindi.'),
                    'photo_url' => $user->profile_photo_url
                ]);
            }
            
            // Profil sayfasına başarı mesajıyla dön
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Profil fotoğrafı başarıyla silindi.'));
                
        } catch (\Exception $e) {
            Log::error('User profile photo delete error: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Profil fotoğrafı silinirken bir hata oluştu.')
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', __('Profil fotoğrafı silinirken bir hata oluştu.'));
        }
    }
    
    /**
     * Kullanıcı güvenlik sayfasını gösterme
     * Display user security page
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function security($id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            return view('content.admin.users.user-profile-security-update', [
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('User security page error: ' . $e->getMessage());
            return redirect()->route('admin.users')
                ->with('error', __('Güvenlik sayfası yüklenirken bir hata oluştu.'));
        }
    }
    
    /**
     * Kullanıcı şifresini güncelleme
     * Update user password
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        try {
            // Kullanıcıyı bul
            $user = User::findOrFail($id);
            
            // Yalnızca form verileri validasyonu
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail(__('Mevcut şifre yanlış.'));
                    }
                }],
                'password' => 'required|string|min:4|confirmed',
                'password_confirmation' => 'required'
            ], [
                'current_password.required' => __('Mevcut şifre alanı gereklidir.'),
                'password.required' => __('Yeni şifre alanı gereklidir.'),
                'password.min' => __('password_length_validation'),
                'password.confirmed' => __('Şifre onayı eşleşmiyor.'),
                'password_confirmation.required' => __('Şifre onayı alanı gereklidir.')
            ]);
            
            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Form doğrulama hatası.'),
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
            }
            
            // Şifre güncelleme
            $user->password = Hash::make($request->input('password'));
            $user->save();
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Şifre başarıyla güncellendi.')
                ]);
            }
            
            // Normal istek ise sayfaya yönlendir
            return redirect()->route('admin.users.profile.security', $user->id)
                ->with('success', __('Şifre başarıyla güncellendi.'));
                
        } catch (\Exception $e) {
            Log::error('User password update error: ' . $e->getMessage());
            
            // AJAX isteği veya JSON yanıtı isteniyorsa
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Şifre güncellenirken bir hata oluştu.')
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', __('Şifre güncellenirken bir hata oluştu.'))
                ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
        }
    }
}