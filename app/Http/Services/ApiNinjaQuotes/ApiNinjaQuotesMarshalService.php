<?php
/**
 * ApiNinjaQuotesMarshalService.php
 * Description: This class is in charge of marshalling the ApiNinja service
 * Author: Hernan Arregoces
 */

namespace App\Http\Services\ApiNinjaQuotes;

use App\Http\Interfaces\QuoteMarshalService;
use App\Http\Services\Quote;

class ApiNinjaQuotesMarshalService implements QuoteMarshalService
{
    /**
     * marshal string to quote object or array of quote object
     */
    public function marshal(string $response) : array
    {
        $quotes = json_decode($response, true);
        $quoteObjects = [];
        foreach ($quotes as $quote) {
            $quoteObjects[] = new Quote(
                $quote['quote']??'',
                $quote['author']??'',
            );
        }
        return $quoteObjects;
    }
}
