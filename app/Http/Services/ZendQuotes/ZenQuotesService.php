<?php

/**
 * ZenQuotesService.php
 * Description: This service is responsible for fetching quotes from zenquotes.io
 * Author: Hernan Arregoces
 * implement: quoteServiceInterface
 */

namespace App\Http\Services\ZendQuotes;
use App\Http\Interfaces\QuoteServiceInterface;

class ZenQuotesService implements QuoteServiceInterface
{

    private $apiUrl = 'https://zenquotes.io/api/';

    /**
     * getQuotes
     * Description: This method fetches quotes from zenquotes.io
     * Parameter: int $numberOfQuotes: number of quotes to fetch
     * @param int $numberOfQuotes
     * @return string
     */
    public function getQuotes(int $numberOfQuotes = 5) : string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . 'quotes?limit=' . $numberOfQuotes,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * getTodayQuote
     * Description: This method fetches the today quote from zenquotes.io
     * Parameter: int $numberOfQuotes: number of quotes to fetch
     * @return string
     */
    public function getTodayQuote() : string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . 'today',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
