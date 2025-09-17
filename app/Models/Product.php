<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, Translatable;
    
    public $table = 'products';
    
    public array $translatedAttributes = [
        'online', 'name', 'slug', 'description', 'specifications', 'advantages', 'usages', 'accessories',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $indexes = [
        'name',
        'online',
    ];
    
    protected $appends = [
        'photo',
        'main_photo',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $fillable = [
        'application_id',
        'category_id',
        'filter_id',
        'online',
        'brand_id',
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
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }
    
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
    
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function filter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }
    
    public function Productfiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Productfile::class);
    }
    
    public function getPhotoAttribute(): \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection
    {
        $files = $this->getMedia('photo');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });
        
        return $files;
    }
    
    public function getFirstPhotoAttribute()
    {
        $file = $this->getMedia('photo')->first();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getMainPhotoAttribute()
    {
        $file = $this->getMedia('main_photo')->last();
        if ($file) {
            $file->url = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview = $file->getUrl('preview');
        }
        
        return $file;
    }
    
    public function getMetaImage(): ?array
    {
        $mainPhoto = $this->getMainPhotoAttribute();
        
        if ($mainPhoto) {
            return [
                'url' => $mainPhoto->getUrl(),
                'name' => $this->slug,
                'width' => 600,
                'height' => 600,
            ];
        }
        
        return null;
    }
    
    public function references(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'product_reference', 'product_id', 'reference_id');
    }
}
