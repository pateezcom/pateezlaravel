<?php

namespace App\Models\Admin\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Permission;

class UserPermission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Modelle ilişkilendirilmiş tablo.
     *
     * @var string
     */
    protected $table = 'model_has_permissions';

    /**
     * The attributes that are mass assignable.
     * Toplu atanabilir özellikler.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id',
    ];

    /**
     * Get the user that owns the permission.
     * Bu izne sahip kullanıcıyı getir.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'model_id');
    }

    /**
     * Get the permission associated with this user permission.
     * Bu kullanıcı izniyle ilişkili izni getir.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
