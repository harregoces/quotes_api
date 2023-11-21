<?php
/**
 * ApiNinjaQuotesService.php
 * Description: This service is responsible for fetching quotes from api.ninja
 * Author: Hernan Arregoces
 */
namespace App\Http\Services\ApiNinjaQuotes;

use App\Http\Interfaces\QuoteServiceInterface;

class ApiNinjaQuotesService implements QuoteServiceInterface
{

    private $apiUrl = 'https://api.api-ninjas.com/v1/';
    private $token = '';

    public function __construct()
    {
        $this->token = env('API_NINJA_TOKEN');
    }

    /**
     * getQuotes
     * Description: This method fetches quotes from api.ninja
     * Example: https://api.api-ninjas.com/v1/quotes?X-Api-Key=$token
     * Parameter: int $numberOfQuotes: number of quotes to fetch
     * @param int $numberOfQuotes
     * @return string
     */
    public function getQuotes(int $numberOfQuotes) : string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . 'quotes?X-Api-Key=' . $this->token,
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
