<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['online','company','content','name','job'];

    public static $searchable = [
        'company',
        'content',
        'name',
        'job'
    ];
}
