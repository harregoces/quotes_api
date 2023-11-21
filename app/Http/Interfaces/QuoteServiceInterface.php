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
     * Parameter: int $userid: The user id
     * @param int $userid
     * @return string
     */
    public function getQuotes(int $userid): string;

    /**
     * getTodayQuote
     * Description: This method fetches the today quote from a quote service
     * @return string
     */
    public function getTodayQuote(): string;
}
