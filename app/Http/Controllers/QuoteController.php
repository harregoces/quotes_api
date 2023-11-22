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
     * Api endpoint /api/today
     * Type: GET
     * Description: Returns a quote for non-authenticated user
     */
    public function today(Request $request): \Illuminate\Http\JsonResponse
    {
        $quote = $this->quoteService->getTodayQuote();
        return response()->json([
            'quote' => $quote
        ], 200);
    }

    /**
     * Api endpoint /api/today/new
     * Type: GET
     * Description: clear cache and returns a quote for non-authenticated user
     */
    public function newToday(Request $request): \Illuminate\Http\JsonResponse
    {
        $quote = $this->quoteService->getTodayQuote(true);
        return response()->json([
            'quote' => $quote
        ], 200);
    }


    /**
     * Api endpoint /api/favoriteQuotes
     * Type: GET
     * Description: Returns favorite quotes for authenticated user
     */
    public function favoriteQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quoteLimit = $request->input('quoteLimit', 10);
        $quotes = $this->quoteService->getFavoriteQuotes($request->user()->id, null, $quoteLimit);
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
        $quotes = $this->quoteService->getUnSecureQuotes($quoteLimit, CacheServiceInterface::FIVE_QUOTES_KEY);
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
        $quotes = $this->quoteService->getUnSecureQuotes($quoteLimit, CacheServiceInterface::FIVE_QUOTES_KEY, true);
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
        $user = $request->user();
        $quoteLimit = $request->input('quoteLimit', 10);
        $quotes = $this->quoteService->getSecureQuotes($user->id, $quoteLimit, CacheServiceInterface::TEN_SECURE_QUOTES_KEY );
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
        $user = $request->user();
        $quoteLimit = $request->input('quoteLimit', 10);
        $quotes = $this->quoteService->getSecureQuotes($user->id, $quoteLimit, CacheServiceInterface::TEN_SECURE_QUOTES_KEY, true);
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
        $quotes = $this->quoteService->getQuotesForUnAuthenticatedUsers(5, CacheServiceInterface::FIVE_QUOTES_KEY, $new);
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/favorite-quotes
     * Type: POST
     * Description: Marks a quote as favorite for authenticated user
     */
    public function saveFavoriteQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quote = new Quote(
            $request->input('quote.quote'),
            $request->input('quote.author')
        );

        $quoteSaved = $this->quoteService->saveFavoriteQuote($request->user()->id, $quote);
        return response()->json([
            'quote' => $quoteSaved
        ], 200);
    }
}
