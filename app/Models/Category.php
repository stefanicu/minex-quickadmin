<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia, TranslatableContract
{
    use InteractsWithMedia, Translatable;
    
    public $table = 'categories';
    
    public $translatedAttributes = [
        'online', 'name', 'slug', 'content', 'call_to_action', 'call_to_action_link',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
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
        'application_id',
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
    
    protected function serializeDate(DateTimeInterface $date): string
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
    
    public function getMetaImage(): ?array
    {
        $mainPhoto = $this->getCoverPhotoAttribute();
        
        if ($mainPhoto) {
            return [
                'url' => $mainPhoto->getUrl(),
                'name' => $this->slug,
                'width' => 1920,
                'height' => 540,
            ];
        }
        
        return null;
    }
    
    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
    
    public function filters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Filter::class);
    }
    
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
