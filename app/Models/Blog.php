<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Intervention\Image\Facades\Image;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable;
    
    public $table = 'blogs';
    
    public array $translatedAttributes = [
        'online', 'name', 'slug', 'content', 'image_text',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
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
        'oldarticletype',
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
        $this->addMediaConversion('thumb')->width(75);
        $this->addMediaConversion('preview')->width(288);
        $this->addMediaConversion('thumb_webp')->width(75)->format(Manipulations::FORMAT_WEBP);
        $this->addMediaConversion('preview_webp')->width(288)->format(Manipulations::FORMAT_WEBP);
        $this->addMediaConversion('original_webp')->format(Manipulations::FORMAT_WEBP);
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
