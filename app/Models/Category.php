<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, HasFactory, Translatable;

    public $table = 'categories';
    public $translatedAttributes = ['online','name','slug'];

    protected $appends = [
        'cover_photo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'online',
        'page_views',
        'product_image_id',
        'oldid',
        'oldimage',
        'oldgroupid',
        'oldproductid',
        'oldproductimg',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function categoriesProducts()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getCoverPhotoAttribute()
    {
        $file = $this->getMedia('cover_photo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function product_image()
    {
        return $this->belongsToMany(Product::class);
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class);
    }
}
