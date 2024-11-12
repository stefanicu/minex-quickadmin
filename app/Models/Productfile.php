<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productfile extends Model implements TranslatableContract
{
    use SoftDeletes, HasFactory, Translatable;

    public $table = 'productfiles';

    public $translatedAttributes = ['online','title'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'name',
        'languages',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function getFileAttribute()
    {
        $files = $this->getMedia('file');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
        });

        return $files;
    }
}
