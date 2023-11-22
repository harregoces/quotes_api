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
     * @param int|null $quoteId
     * @param int $quoteLimit
     * @return array
     */
    public function getFavoriteQuotes(int $userId, int $quoteId = null, int $quoteLimit = 5): array
    {
        $quotesQuery = $this->quotesFavoritesModel->where('user_id', $userId);
        if ($quoteId) {
            $quotesQuery->where('quote_id', $quoteId);
        }
        $quotesRecords = $quotesQuery->limit($quoteLimit)
            ->get();
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

    /**
     * saveFavoriteQuote
     * Description: This method saves a quote as favorite for specific user
     * @param int $userId
     * @param Quote $quote
     * @return Quote
     */
    public function saveFavoriteQuote(int $userId, Quote $quote): Quote
    {
        $quoteRecord = $this->quotesModel->where('id', $quote->getId())->first();
        if (!$quoteRecord) {
            $quoteRecord = new QuoteModel();
            $quoteRecord->quote = $quote->getQuote();
            $quoteRecord->author = $quote->getAuthor();
            $quoteRecord->save();
        }
        $quoteFavoriteRecord = $this->quotesFavoritesModel->where('user_id', $userId)->where('quote_id', $quoteRecord->id)->first();
        if (!$quoteFavoriteRecord) {
            $quoteFavoriteRecord = new QuotesFavoritesModel();
            $quoteFavoriteRecord->user_id = $userId;
            $quoteFavoriteRecord->quote_id = $quoteRecord->id;
            $quoteFavoriteRecord->save();
        }

        return new Quote($quoteRecord->quote, $quoteRecord->author, $quoteRecord->id);
    }

    /**
     * getAllFavoriteQuotes
     * Description: This method returns a report of favorite quotes for all users
     * @return array
     */
    public function getAllFavoriteQuotes(): array
    {
        $quotesRecords = $this->quotesFavoritesModel->all();
        $allFavorites = [];
        foreach ($quotesRecords as $quoteRecord) {
            $allFavorites[] = [
                'user' => [
                    'id' => $quoteRecord->user()->id,
                    'email' => $quoteRecord->user()->email,
                    'name' => $quoteRecord->user()->name,
                ],
                'quote' => [
                    'id' => $quoteRecord->quote()->id,
                    'quote' => $quoteRecord->quote()->quote,
                    'author' => $quoteRecord->quote()->author,
                ]
            ];
        }
        return $allFavorites;
    }
}
