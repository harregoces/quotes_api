<?php

/**
 * quoteServiceInterface.php
 * Description: This interface defines the contract for all quote services
 */

namespace App\Http\Interfaces;

interface QuoteServiceInterface
{
    /**
     * getQuotes
     * Description: This method fetches quotes from a quote service
     * Parameter: int $numberOfQuotes: number of quotes to fetch
     * @param int $numberOfQuotes
     * @return string
     */
    public function getQuotes(int $numberOfQuotes): string;

    /**
     * getTodayQuote
     * Description: This method fetches the today quote from a quote service
     * @return string
     */
    public function getTodayQuote(): string;
}
