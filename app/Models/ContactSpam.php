<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSpam extends Model
{
    use SoftDeletes;
    
    public $table = 'contact_spams';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public static $searchable = [
        'name',
        'surname',
        'email',
        'job',
        'industry',
        'how_about',
        'message',
        'company',
        'phone',
        'country',
        'county',
        'city',
        'product',
        'ip',
        'district',
    ];
    
    protected $fillable = [
        'name',
        'surname',
        'email',
        'job',
        'industry',
        'how_about',
        'message',
        'company',
        'phone',
        'country',
        'county',
        'city',
        'checkbox',
        'product',
        'ip',
        'district',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
