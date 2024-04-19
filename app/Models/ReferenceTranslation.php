<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['online','name','slug','content','text_img1','text_img2','text_img3','text_img4','text_img5'];

    public static $searchable = [
        'name',
        'slug',
        'content',
    ];
}
