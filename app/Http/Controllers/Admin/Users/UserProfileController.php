<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Admin\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    'alpha_dash',
                    Rule::unique('users')->ignore($user->id),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
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
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Slug yoksa username'den oluştur
            if (empty($request->input('slug'))) {
                $slug = Str::slug($request->input('username'));
                
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
            
            // Profil sayfasına başarı mesajıyla dön
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Hesap bilgileri başarıyla güncellendi.'));
                
        } catch (\Exception $e) {
            Log::error('User account update error: ' . $e->getMessage());
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
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Profil fotoğrafını güncelle (HasProfilePhoto trait'i kullanarak)
            if ($request->hasFile('profile_photo')) {
                $user->updateProfilePhoto($request->file('profile_photo'));
            }
            
            // Profil sayfasına başarı mesajıyla dön
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Profil fotoğrafı başarıyla güncellendi.'));
                
        } catch (\Exception $e) {
            Log::error('User profile photo update error: ' . $e->getMessage());
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
            
            // Profil sayfasına başarı mesajıyla dön
            return redirect()->route('admin.users.profile', $user->id)
                ->with('success', __('Profil fotoğrafı başarıyla silindi.'));
                
        } catch (\Exception $e) {
            Log::error('User profile photo delete error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', __('Profil fotoğrafı silinirken bir hata oluştu.'))
                ->withInput();
        }
    }
}
