<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    protected $table = 'brand_translations';
    
    public $timestamps = false;
    protected $fillable = ['online', 'locale', 'offline_message'];
}
