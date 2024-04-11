<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class TranslationCenter extends Model implements TranslatableContract
{
    use SoftDeletes, HasFactory, Translatable;

    public $table = 'translation_centers';
    public $translatedAttributes = ['name'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SECTION_SELECT = [
        'menu'    => 'Menu',
        'forms'   => 'Forms',
        'strings' => 'Strings',
    ];

    protected $fillable = [
        'language',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();
        self::observe(new \App\Observers\TranslationCenterActionObserver);
    }
}
