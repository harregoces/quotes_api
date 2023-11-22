<?php

/**
 * Quote.php
 * Description: This class represents a quote, use factory method to get the quoteService, and use the quoteService to get the quotes
 * Author: Hernan Arregoces
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Quotes as QuotesModel;
use App\Models\User as UserModel;

class QuotesFavorites extends Model
{
    use HasFactory;

    protected $table = 'quotes_favorites';

    protected $fillable = [
        'user_id',
        'quote_id',
    ];

    /**
     * Get the quote.
     */
    public function quotes(): BelongsToMany
    {
        return $this->belongsToMany(QuotesModel::class, 'quotes_favorites', 'id', 'quote_id');
    }

    /**
     * Get the user.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'quotes_favorites', 'id', 'user_id');
    }
}
