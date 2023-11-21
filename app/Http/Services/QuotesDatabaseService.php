<?php

namespace App\Http\Services;

use App\Models\Quotes as QuoteModel;
use App\Models\QuotesFavorites as QuotesFavoritesModel;
use App\Http\Services\Quote as Quote;

class QuotesDatabaseService
{
    /**
     * @var QuoteModel
     */
    private QuoteModel $quotesModel;

    /**
     * $var QuotesFavoritesModel
     */
    private QuotesFavoritesModel $quotesFavoritesModel;

    /**
     * QuoteDatabaseService constructor.
     */
    public function __construct(QuoteModel $quotesModel, QuotesFavoritesModel $quotesFavoritesModel)
    {
        $this->quotesModel = $quotesModel;
        $this->quotesFavoritesModel = $quotesFavoritesModel;
    }

    /**
     * getFavoriteQuotes
     * Description: This method returns the favorite quotes for specific users
     * @param int $userId
     * @param int $quoteLimit
     * @return array
     */
    public function getFavoriteQuotes(int $userId, int $quoteLimit = 5): array
    {
        $quotesRecords = $this->quotesFavoritesModel->where('user_id', $userId)->limit($quoteLimit)->get();
        return $this->transform($quotesRecords);
    }

    /**
     * transform
     * Description: This method transforms the quotes collection to Quotes object
     * @param $quotesRecords
     * @return array
     */
    private function transform($quotesRecords): array
    {
        $quotes = [];
        foreach ($quotesRecords as $quoteRecord) {
            foreach($quoteRecord->quotes as $quoteModel) {
                $quote = new Quote(
                    $quoteModel->quote,
                    $quoteModel->author,
                    $quoteModel->id
                );
                $quotes[] = $quote;
            }

        }
        return $quotes;
    }
}
