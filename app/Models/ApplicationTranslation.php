<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'name', 'slug',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $indexes = [
        'application_id',
        'name',
        'online',
    ];
    
    public static $searchable = [
        'name',
        'slug',
    ];
}
