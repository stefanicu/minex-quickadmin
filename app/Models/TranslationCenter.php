<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslationCenter extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'translation_centers';

    public static $searchable = [
        'name',
        'slug',
        'section',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const LANGUAGE_SELECT = [
        'en' => 'English',
        'ro' => 'Romanian',
        'bg' => 'Bulgarian',
    ];

    public const SECTION_SELECT = [
        'menu'    => 'Menu',
        'forms'   => 'Forms',
        'strings' => 'Strings',
    ];

    protected $fillable = [
        'online',
        'language',
        'name',
        'slug',
        'section',
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
