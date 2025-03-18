<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model implements TranslatableContract
{
    use Translatable;
    
    public $table = 'filters';
    
    public $translatedAttributes = [
        'online', 'name', 'slug',
        'meta_title', 'meta_description', 'author', 'robots', 'canonical_url'
    ];
    
    protected $indexes = [
        'name',
        'online',
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $fillable = [
        'online',
        'category_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
