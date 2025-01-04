<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontPageTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name', 'first_text', 'second_text', 'quote', 'button'];
}
