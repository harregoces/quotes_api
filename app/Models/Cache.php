<?php

/**
 * Cache.php
 * Description: Model for the table cache, to store the key, value and expiration time
 * Author: Hernan Arregoces
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'userid',
        'value',
        'expiration_time'
    ];

    protected $table = 'cache';

    public $timestamps = false;
}
