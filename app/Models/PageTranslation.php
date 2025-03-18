<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'name', 'slug', 'content', 'call_to_action', 'call_to_action_link', 'image_text',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
}
