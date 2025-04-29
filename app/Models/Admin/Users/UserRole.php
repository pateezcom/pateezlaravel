<?php

namespace App\Models\Admin\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Users\User;
use Spatie\Permission\Models\Role;

class UserRole extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Modelle ilişkilendirilmiş tablo.
     *
     * @var string
     */
    protected $table = 'model_has_roles';

    /**
     * The attributes that are mass assignable.
     * Toplu atanabilir özellikler.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    /**
     * Get the user that owns the role.
     * Bu role ait kullanıcıyı getir.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'model_id');
    }

    /**
     * Get the role associated with this user role.
     * Bu kullanıcı rolüyle ilişkili rolü getir.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
