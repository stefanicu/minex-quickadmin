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

class Application extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable;
    
    public $table = 'applications';
    
    public $translatedAttributes = [
        'online', 'name', 'slug',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $indexes = [
        'name',
        'online',
    ];
    
    protected $appends = [
        'image',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $fillable = [
        'online',
        'oldid',
        'oldimage',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    //    protected static function booted(): void
    //    {
    //        static::addGlobalScope(new ApplicationScope,function (Builder $builder) {
    //            $builder->all();
    //        });
    //    }
    
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
    
    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getMetaImage(): ?array
    {
        $mainPhoto = $this->getImageAttribute(); // Replace with your logic to get the main photo
        
        if ($mainPhoto) {
            return [
                'url' => $mainPhoto->getUrl('preview'),
                'name' => $this->slug,
                'width' => 1920,
                'height' => 540,
            ];
        }
        
        return null;
    }
    
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
