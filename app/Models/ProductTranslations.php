<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslations extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'online',
        'name',
        'slug',
        'description',
        'specifications',
        'advantages',
        'usages',
        'accessories'
    ];

    public static $searchable = [
        'name',
        'slug',
        'description',
        'specifications',
        'advantages',
        'usages',
        'accessories'
    ];
}
