<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\CacheServiceInterface;
use App\Http\Services\Quote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Services\QuoteService;

class QuoteController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private QuoteService $quoteService;

    /**
     * QuoteController constructor.
     * @param QuoteService $quoteService
     */
    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    /**
     * Api endpoint /api/favoriteQuotes
     * Type: GET
     * Description: Returns favorite quotes for authenticated user
     */
    public function favoriteQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 10);
        $user = $request->user();
        $quotes = $this->quoteService->getFavoriteQuotes($user->id, $quoteLimit);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/secure-quotes
     * Type: GET
     * Description: Returns quotes for authenticated user
     */
    public function secureQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 10);
        $quotes = $this->quoteService->getSecureQuotes($quoteLimit, CacheServiceInterface::TEN_SECURE_QUOTES_KEY );
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/secure-quotes/new
     * Type: GET
     * Description: clear cache and returns quotes for authenticated user
     */
    public function newSecureQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 10);
        $quotes = $this->quoteService->getSecureQuotes($quoteLimit, CacheServiceInterface::TEN_SECURE_QUOTES_KEY, true);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Console command: php artisan Get-FiveRandomQuotes --new
     * Description: Returns five random quotes for non-authenticated user
     * @param bool $new
     * @return JsonResponse
     */
    public function getFiveRandomQuotes(bool $new = false): \Illuminate\Http\JsonResponse
    {
        $quotes = $this->quoteService->getQuotes(5, $new);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/quotes
     * Type: GET
     * Description: Returns five random quotes for non-authenticated user and authenticated user
     */
    public function quotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 5);
        $quotes = $this->quoteService->getQuotes($quoteLimit, CacheServiceInterface::FIVE_QUOTES_KEY);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/quotes/new
     * Type: GET
     * Description: clear cache and returns five random quotes for non-authenticated user and authenticated user
     */
    public function newQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 5);
        $quotes = $this->quoteService->getQuotes($quoteLimit, CacheServiceInterface::FIVE_QUOTES_KEY, true);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /saveFavorite
     * Type: POST
     * Description: Marks a quote as favorite for authenticated user
     */
    public function saveFavorite(Request $request): \Illuminate\Http\JsonResponse
    {
        /**
         * Get a quote from request and save it in the favorite quotes table
         */
        $quote = new Quote(
            $request->input('quote'),
            $request->input('author'),
        );

        $this->quoteService->saveFavorite($quoteId);
        return response()->json([
            'message' => 'Quote saved as favorite'
        ], 200);
    }
}
