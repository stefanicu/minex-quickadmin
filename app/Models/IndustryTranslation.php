<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['online', 'name', 'slug'];
}
