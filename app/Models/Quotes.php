<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    use HasFactory;

    protected $table = 'quotes';

    protected $fillable = [
        'quote',
        'author',
    ];

    /**
     * Get the favorite quotes for the user.
     */
    public function favoriteQuotes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quotes_favorites', 'quote_id', 'user_id');
    }
}
