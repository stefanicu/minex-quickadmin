<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Intervention\Image\Facades\Image;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable, InteractsWithMedia;
    
    public $table = 'pages';
    
    public array $translatedAttributes = [
        'online', 'name', 'slug', 'content', 'call_to_action', 'call_to_action_link', 'image_text',
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
        if ($media?->collection_name === 'ck-media') {
            // Get the path to the uploaded image from the temporary folder
            $image = $media->getPath(); // This gets the full path to the image
            if (file_exists($image)) {
                list($width, $height) = getimagesize($image); // Proceed only if the file exists
            } else {
                $width = 0;
            }
            
            // If image width is greater than 750px, apply the conversion
            if ($width > 750) {
                $this->addMediaConversion('ckeditor')
                    ->width(750)  // Resize to max width of 750px
                    ->keepOriginalImageFormat()  // Maintain original format
                    ->performOnCollections('ck-media');
            } else {
                // If the image is smaller than 750px, keep the original
                $this->addMediaConversion('ckeditor')
                    ->keepOriginalImageFormat()  // No resizing, keep original
                    ->performOnCollections('ck-media');
            }
            $this->addMediaConversion('preview')->width(288);
        } else {
            $this->addMediaConversion('thumb')->width(100);
            $this->addMediaConversion('preview')->width(432);
            
            $this->addMediaConversion('xl')->fit('crop', 1920, 580);
            //        $this->addMediaConversion('lg')->fit( 'crop',1366,400);
            //        $this->addMediaConversion('md')->fit( 'crop',768,400);
            //        $this->addMediaConversion('sm')->fit('crop', 425,200);
            
            $this->addMediaConversion('xl_webp')->fit('crop', 1920, 580)->format(Manipulations::FORMAT_WEBP);
            //        $this->addMediaConversion('lg_webp')->fit( 'crop',1366,400)->format(Manipulations::FORMAT_WEBP);
            //        $this->addMediaConversion('md_webp')->fit( 'crop',768,400)->format(Manipulations::FORMAT_WEBP);
            //        $this->addMediaConversion('sm_webp')->fit('crop', 425,200)->format(Manipulations::FORMAT_WEBP);
        }
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
        $mainPhoto = $this->getImageAttribute();
        
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
