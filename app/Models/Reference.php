<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Intervention\Image\Facades\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Reference extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable;
    
    public $table = 'references';
    public $translatedAttributes = [
        'online', 'name', 'slug', 'content', 'text_img1', 'text_img2', 'text_img3', 'text_img4', 'text_img5',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $appends = [
        'photo_square',
        'photo_wide',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $fillable = [
        'industries_id',
        'online',
        'oldid',
        'oldimage_1',
        'oldimage_2',
        'oldimage_3',
        'oldimage_4',
        'oldimage_5',
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
        $this->addMediaConversion('thumb')->height(50);
        $this->addMediaConversion('preview')->height(120);
    }
    
    public function Products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_reference', 'reference_id', 'product_id');
    }
    
    public function industries()
    {
        return $this->belongsTo(Industry::class, 'industries_id');
    }
    
    public function getPhotoSquareAttribute()
    {
        $files = $this->getMedia('photo_square');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });
        
        return $files;
    }
    
    public function getLastPhotoSquareAttribute()
    {
        $file = $this->getMedia('photo_square')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getPhotoWideAttribute()
    {
        $file = $this->getMedia('photo_wide')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getMetaImage(): ?array
    {
        $mainPhoto = $this->getPhotoWideAttribute();
        
        if ($mainPhoto) {
            $image = Image::make($mainPhoto->getPath());
            return [
                'url' => $mainPhoto->getUrl(),
                'name' => $this->slug,
                'width' => $image->width(),
                'height' => $image->height(),
            ];
        }
        
        return null;
    }
}
