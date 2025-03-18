<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'name', 'slug',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
}
