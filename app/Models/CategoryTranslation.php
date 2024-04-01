<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['online','name','slug'];

//    public static $searchable = [
//        'name',
//        'slug',
//    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
//    public function sluggable(): array
//    {
//        return [
//            'slug' => [
//                'source' => 'name'
//            ]
//        ];
//    }
}
