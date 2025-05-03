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

    protected $table = 'users';

    protected $fillable = [
        'name', 'username', 'slug', 'email', 'phone', 'password', 'status',
        'reward_system_active', 'profile_photo_path', 'about_me', 'facebook',
        'twitter', 'instagram', 'tiktok', 'whatsapp', 'youtube', 'discord',
        'telegram', 'pinterest', 'linkedin', 'twitch', 'vk', 'personal_website_url'
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reward_system_active' => 'boolean',
        'status' => 'integer'
    ];

    public function isActive()
    {
        return $this->status === 2;
    }

    public function isRewardSystemActive()
    {
        return $this->reward_system_active === true;
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return url('storage/' . $this->profile_photo_path);
        }
        return $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function updateProfilePhoto($photo)
    {
        $this->deleteProfilePhoto();
        $this->forceFill([
            'profile_photo_path' => $photo->storePublicly('profile-photos', ['disk' => $this->profilePhotoDisk()])
        ])->save();
    }

    public function deleteProfilePhoto()
    {
        if (!is_null($this->profile_photo_path) && Storage::disk($this->profilePhotoDisk())->exists($this->profile_photo_path)) {
            Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);
        }
        $this->forceFill(['profile_photo_path' => null])->save();
    }

    protected function profilePhotoDisk()
    {
        return config('jetstream.profile_photo_disk', 'public');
    }
}