<?php

namespace App\Models\Admin\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'slug',
        'email',
        'password',
        'status',
        'reward_system_active',
        'profile_photo_path',
        'about_me',
        'facebook',
        'twitter',
        'instagram',
        'tiktok',
        'whatsapp',
        'youtube',
        'discord',
        'telegram',
        'pinterest',
        'linkedin',
        'twitch',
        'vk',
        'personal_website_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reward_system_active' => 'boolean',
        'status' => 'integer'
    ];

    /**
     * Kullanıcı durumunu kontrol eden method
     * Checks if the user is active
     * 
     * Status values:
     * 0 = Pending
     * 1 = Inactive
     * 2 = Active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 2;
    }

    /**
     * Ödül sisteminin aktif olup olmadığını kontrol eden method
     * Checks if the reward system is active for the user
     *
     * @return bool
     */
    public function isRewardSystemActive()
    {
        return $this->reward_system_active === true;
    }

    /**
     * Profil fotoğrafı URL'si
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return url('storage/'.$this->profile_photo_path);
        }
        
        return $this->defaultProfilePhotoUrl();
    }
    
    /**
     * Varsayılan profil fotoğrafı URL'si
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }
    
    /**
     * Profil fotoğrafını güncelleme
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto($photo)
    {
        $this->deleteProfilePhoto();

        $this->forceFill([
            'profile_photo_path' => $photo->store(
                'profile-photos', ['disk' => $this->profilePhotoDisk()]
            ),
        ])->save();
    }
    
    /**
     * Profil fotoğrafını silme
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (! is_null($this->profile_photo_path) && 
            Storage::disk($this->profilePhotoDisk())->exists($this->profile_photo_path)) {
            Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);
        }

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }
    
    /**
     * Profil fotoğrafı disk adı
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return config('jetstream.profile_photo_disk', 'public');
    }
    
    /**
     * Kullanıcının belirli bir role sahip olup olmadığını kontrol eder
     * Checks if user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    
    /**
     * Kullanıcının belirli bir izne sahip olup olmadığını kontrol eder
     * Checks if user has a specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->getAllPermissions()->contains('name', $permission);
    }
}
