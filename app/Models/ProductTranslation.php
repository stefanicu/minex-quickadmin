<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = 'product_translations';

    public $timestamps = false;

    protected $indexes = [
        'product_id',
        'name',
        'online',
    ];

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
