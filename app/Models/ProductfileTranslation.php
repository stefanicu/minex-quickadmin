<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductfileTranslation extends Model
{
    protected $table = 'productfile_translations';

    public $timestamps = false;

    protected $indexes = [
        'productfiles_id',
        'title',
        'online',
    ];

    protected $fillable = [
        'online',
        'title',
    ];

}
