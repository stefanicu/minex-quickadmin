<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'contacts';

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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
