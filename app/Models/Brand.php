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

class Brand extends Model implements HasMedia, TranslatableContract
{
    use InteractsWithMedia, Translatable;
    
    public $table = 'brands';
    
    public array $translatedAttributes = [
        'online', 'offline_message', 'content', 'call_to_action', 'call_to_action_link',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $appends = [
        'photo',
    ];
    
    public static $searchable = [
        'name',
        'slug',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $fillable = [
        'online',
        'name',
        'slug',
        'offline_message',
        'oldid',
        'oldimage',
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
        $this->addMediaConversion('thumb')->fit(Manipulations::FIT_MAX, 75)->optimize();
        $this->addMediaConversion('preview')->fit(Manipulations::FIT_MAX, 120)->optimize();
    }
    
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
    
    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getMetaImage(): ?array
    {
        $mainPhoto = $this->getPhotoAttribute(); // Replace with your logic to get the main photo
        
        if ($mainPhoto) {
            return [
                'url' => $mainPhoto->getUrl(),
                'name' => $this->slug,
                'width' => 343,
                'height' => 228,
            ];
        }
        
        return null;
    }
}
