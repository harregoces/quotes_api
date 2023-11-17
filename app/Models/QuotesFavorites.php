<?php

/**
 * Quote.php
 * Description: This class represents a quote, use factory method to get the quoteService, and use the quoteService to get the quotes
 * Author: Hernan Arregoces
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotesFavorites extends Model
{
    use HasFactory;

    protected $table = 'quotes_favorites';

    protected $fillable = [
        'user_id',
        'quote_id',
    ];
}
