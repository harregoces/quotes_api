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
            $newQuotes = $this->quotesService->getQuotes($userid);
            $newQuotes = $this->quotesMarshalService->marshal($newQuotes);
            $quotes = array_merge($quotes, $newQuotes);
            $this->cacheService->set($key, $userid, $quotes);
            return (array) array_slice($quotes, 0, $quoteLimit);
        } else {
            $quotes = $this->quotesService->getQuotes($userid);
            $this->cacheService->set($key, $userid, $quotes);
            return array_slice($this->quotesMarshalService->marshal($quotes), 0, $quoteLimit);
        }
    }

    /**
     * getFavoriteQuotes
     * Description: This method saves a quote as favorite for authenticated users
     * @param int $quoteId
     * @return array
     */
    public function getFavoriteQuotes(int $userId, int $quoteId): array
    {
        return $this->quotesDatabaseService->getFavoriteQuotes($userId);
    }
}
