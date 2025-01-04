<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    protected $table = 'category_translations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'name', 'slug',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $indexes = [
        'category_id',
        'name',
        'online',
    ];
    
    public static $searchable = [
        'name',
        'slug',
    ];
}
