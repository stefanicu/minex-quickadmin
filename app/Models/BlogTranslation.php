<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online', 'name', 'slug', 'content', 'image_text',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Blog::updateOnlineStatus($model->blog_id);
        });

        static::deleted(function ($model) {
            Blog::updateOnlineStatus($model->blog_id);
        });
    }
}
