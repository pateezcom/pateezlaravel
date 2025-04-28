<?php

namespace App\Models\Admin\Settings\Language;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Translation Model
 * Çeviri Modeli
 * 
 * This model represents a translation for a language.
 * Bu model, bir dilin çevirilerini temsil eder.
 */
class Translation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Model ile ilişkilendirilen tablo.
     *
     * @var string
     */
    protected $table = 'translations';

    /**
     * The attributes that are mass assignable.
     * Toplu atanabilir özellikler.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'group',
        'key',
        'value',
    ];

    /**
     * Get the language that owns the translation.
     * Bu çeviriye sahip olan dili getir.
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
