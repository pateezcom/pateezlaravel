<?php

namespace App\Models\Admin\Settings\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Language Model
 * Dil Modeli
 * 
 * This model represents a language in the system.
 * Bu model, sistemdeki dilleri temsil eder.
 */
class Language extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Model ile ilişkilendirilen tablo.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     * Toplu atanabilir özellikler.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'icon',
        'is_rtl',
        'is_default',
        'is_active',
        'text_editor_lang',
    ];

    /**
     * The attributes that should be cast.
     * Dönüştürülmesi gereken özellikler.
     *
     * @var array
     */
    protected $casts = [
        'is_rtl' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all translations for this language.
     * Bu dile ait tüm çevirileri getir.
     */
    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_id');
    }
}
