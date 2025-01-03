<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable;
    
    public $table = 'categories';
    
    public array $translatedAttributes = ['online', 'name', 'slug'];
    
    protected $indexes = [
        'name',
        'online',
    ];
    
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
        $this->addMediaConversion('thumb')->width(100);
        $this->addMediaConversion('preview')->width(432);
        
        $this->addMediaConversion('xl')->fit('crop', 1920, 540);
//        $this->addMediaConversion('lg')->fit( 'crop',1366,400);
//        $this->addMediaConversion('md')->fit( 'crop',768,400);
//        $this->addMediaConversion('sm')->fit('crop', 425,200);
        
        $this->addMediaConversion('xl_webp')->fit('crop', 1920, 540)->format(Manipulations::FORMAT_WEBP);
//        $this->addMediaConversion('lg_webp')->fit( 'crop',1366,400)->format(Manipulations::FORMAT_WEBP);
//        $this->addMediaConversion('md_webp')->fit( 'crop',768,400)->format(Manipulations::FORMAT_WEBP);
//        $this->addMediaConversion('sm_webp')->fit('crop', 425,200)->format(Manipulations::FORMAT_WEBP);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    public function getCoverPhotoAttribute()
    {
        $file = $this->getMedia('cover_photo')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function product_main_image()
    {
        return $this->belongsTo(Product::class, 'product_image_id', 'id');
    }
    
    public function applications()
    {
        return $this->belongsToMany(Application::class);
    }
}
