<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductfileTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'online',
        'title',
    ];
    
}
