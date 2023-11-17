<?php
/**
 * ZenQuotesMarshalService.php
 * Description: This class is in charge of marshalling the ZenQuotes service
 * Author: Hernan Arregoces
 */

namespace App\Http\Services\ZendQuotes;

use App\Http\Interfaces\QuoteMarshalService;
use App\Http\Services\Quote;

class ZenQuotesMarshalService implements QuoteMarshalService
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
                $quote['q']??'',
                $quote['a']??'',
            );
        }
        return $quoteObjects;
    }
}
