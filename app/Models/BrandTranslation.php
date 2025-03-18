<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'offline_message', 'content', 'call_to_action', 'call_to_action_link',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
}
