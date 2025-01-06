<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
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
        'accessories',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
}
