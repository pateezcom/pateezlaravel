<?php

namespace App\Models\Admin\Users;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
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
        'email',
        'password',
        'status',
        'reward_system_active',
        'profile_photo_path'
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
        'reward_system_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Kullanıcı durumunu kontrol eden method
     * Checks if the user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
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
     * Varsayılan profil fotoğrafı
     * Default profile photo
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path 
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
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
