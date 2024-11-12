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

class Testimonial extends Model implements HasMedia, TranslatableContract
{
    use SoftDeletes, InteractsWithMedia, HasFactory, Translatable;

    public $table = 'testimonials';
    public $translatedAttributes = ['online','company','content','name','job'];

    protected $appends = [
        'logo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'online',
        'language',
        'oldid',
        'oldimage',
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
        $this->addMediaConversion('thumb')->height( 50);
        $this->addMediaConversion('preview')->height( 120);
    }

    public function getLogoAttribute()
    {
            $file = $this->getMedia('logo')->last();
            if ($file) {
                $file->url       = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
                $file->preview   = $file->getUrl('preview');
            }

            return $file;

    }
}
