<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['online','name','slug'];

    public static $searchable = [
        'name',
        'slug',
    ];
}
