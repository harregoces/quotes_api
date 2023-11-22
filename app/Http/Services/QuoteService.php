<?php

/**
 * Quote.php
 * Description: This class represents a quote, use factory method to get the quoteService, and use the quoteService to get the quotes
 * Author: Hernan Arregoces
 */

namespace App\Http\Services;

use App\Http\Interfaces\QuoteServiceInterface;
use App\Http\Interfaces\QuoteMarshalService;
use App\Http\Interfaces\CacheServiceInterface;
use App\Http\Services\ZendQuotes\ZenQuotesMarshalService;
use App\Http\Services\ZendQuotes\ZenQuotesService;

class QuoteService
{

    /**
     * @var QuoteServiceInterface
     */
    private QuoteServiceInterface $quotesService;

    /**
     * @var QuoteMarshalService
     */
    private QuoteMarshalService $quotesMarshalService;

    /**
     * @var CacheServiceInterface
     */
    private CacheServiceInterface $cacheService;

    /**
     * @var QuotesDatabaseService
     */
    private QuotesDatabaseService $quotesDatabaseService;

    /**
     * Quote constructor.
     */
    public function __construct(QuotesDatabaseService $quotesDatabaseService)
    {
        $this->quotesService = new ZenQuotesService();
        $this->quotesMarshalService = new ZenQuotesMarshalService();
        $this->cacheService = new SqliteCacheService();
        $this->quotesDatabaseService = $quotesDatabaseService;
    }

    /**
     * getTodayQuote
     * Description: This method returns the quote of the day
     * @param bool $force
     * @return Quote
     */
    public function getTodayQuote(bool $force = false): Quote
    {
        if ($force) {
            $this->cacheService->clear($this->cacheService::TODAY_QUOTE_KEY, 0);
        } else {
            $quoteString = $this->cacheService->get($this->cacheService::TODAY_QUOTE_KEY, 0);
            if($quoteString) {
                $quoteMarshal = $this->quotesMarshalService->marshal($quoteString);
                if ($quoteMarshal) {
                    $quote = $quoteMarshal[0];
                    $quote->setCached(true);
                    return $quote;
                }
            }
        }

        $quoteString = $this->quotesService->getTodayQuote();
        $this->cacheService->set($this->cacheService::TODAY_QUOTE_KEY, 0, $quoteString);
        $quote = $this->quotesMarshalService->marshal($quoteString);
        return $quote[0];
    }

    public function getQuotesForAuthenticatedUsers(int$userId, int $quoteLimit, string $key, bool $force = false): array
    {
        return $this->getSecureQuotes($userId, $quoteLimit, $key, $force);
    }

    public function getQuotesForUnAuthenticatedUsers(int $quoteLimit, string $key, bool $force = false): array
    {
        return $this->getUnSecureQuotes($quoteLimit, $key, $force);
    }

    /**
     * getSecureQuotes
     * Description: This method returns the quotes for authenticated users
     * @param int $userId
     * @param int $quoteLimit
     * @param string $key
     * @param bool $force
     * @return array
     */
    public function getSecureQuotes(int $userId, int $quoteLimit, string $key, bool $force = false): array
    {
        return $this->getInternalQuotes($userId, $quoteLimit, $key, $force);
    }

    /**
     * getUnSecureQuotes
     * Description: This method returns the quotes for unauthenticated users
     * @param int $quoteLimit
     * @param string $key
     * @param bool $force
     * @return array
     */
    public function getUnSecureQuotes(int $quoteLimit, string $key, bool $force = false): array
    {
        return $this->getInternalQuotes(0, $quoteLimit, $key, $force);
    }

    /**
     * getQuotes
     * Description: This method returns the quotes
     *  If the quotes are in cache, return the quotes with a quote mark as cached = true in every item and limit the quotes to the quoteLimit,
     *  If we do not reach the quoteLimit, get new quotes from the service and save them in cache to reach the quoteLimit
     * @param int $userid
     * @param int $quoteLimit
     * @param string $key
     * @param bool $force
     * @return array
     */
    private function getInternalQuotes(int $userid, int $quoteLimit, string $key, bool $force = false): array
    {
        if ($force) {
            $this->cacheService->clear($key, $userid);
        }

        $quotes = $this->cacheService->get($key, $userid);

        if ($quotes) {
            $quotes = $this->quotesMarshalService->marshal($quotes);
            $quotes = array_map(function($quote) {
                /**
                 * @var Quote $quote
                 */
                $quote->setCached(true);
                return $quote;
            }, $quotes);
            if (count($quotes) >= $quoteLimit) {
                return (array) array_slice($quotes, 0, $quoteLimit);
            }
            $newQuotes = $this->quotesService->getQuotes();
            $newQuotes = $this->quotesMarshalService->marshal($newQuotes);
            $quotes = array_merge($quotes, $newQuotes);
            $this->cacheService->set($key, $userid, json_encode($quotes));
            return (array) array_slice($quotes, 0, $quoteLimit);
        } else {
            $quotes = $this->quotesService->getQuotes();
            $this->cacheService->set($key, $userid, $quotes);
            return array_slice($this->quotesMarshalService->marshal($quotes), 0, $quoteLimit);
        }
    }

    /**
     * getFavoriteQuotes
     * Description: This method saves a quote as favorite for authenticated users
     * @param int $userId
     * @param int|null $quoteId
     * @param int $quoteLimit
     * @return array
     */
    public function getFavoriteQuotes(int $userId, int $quoteId = null, int $quoteLimit = 5): array
    {
        return $this->quotesDatabaseService->getFavoriteQuotes($userId, $quoteId, $quoteLimit);
    }

    /**
     * getFavoriteQuote
     * Description: This method returns a favorite quote for authenticated users
     * @param int $userId
     * @param int $quoteId
     * @return Quote
     */
    public function getFavoriteQuote(int $userId, int $quoteId): Quote
    {
        return $this->quotesDatabaseService->getFavoriteQuote($userId, $quoteId);
    }

    /**
     * saveFavoriteQuote
     * Description: This method saves a quote as favorite for authenticated users
     * @param int $userId
     * @param Quote $quote
     * @return Quote
     */
    public function saveFavoriteQuote(int $userId, Quote $quote): Quote
    {
        return $this->quotesDatabaseService->saveFavoriteQuote($userId, $quote);
    }

    /**
     * getReportOfFavoriteQuotes
     * Description: This method returns a report of favorite quotes for authenticated users
     * @return array
     */
    public function getReportOfFavoriteQuotes(): array
    {
        return $this->quotesDatabaseService->getAllFavoriteQuotes();
    }

    /**
     * deleteFavoriteQuote
     * Description: This method deletes a favorite quote for authenticated users
     * @param int $userId
     * @param int $quoteId
     */
    public function deleteFavoriteQuote(int $userId, int $quoteId): void
    {
        $this->quotesDatabaseService->deleteFavoriteQuote($userId, $quoteId);
    }
}
