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
        $new = $request->input('new', false);
        $quote = $this->quoteService->getTodayQuote($new);
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
     * Api endpoint /api/favorite-quotes/{id}
     */
    public function getFavoriteQuote(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $quote = $this->quoteService->getFavoriteQuote($request->user()->id, $id);
        return response()->json([
            'quote' => $quote
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
        $new = $request->input('new', false);
        $quotes = $this->quoteService->getUnSecureQuotes($quoteLimit, CacheServiceInterface::FIVE_QUOTES_KEY, $new);
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
        $new = $request->input('new', false);
        $quotes = $this->quoteService->getSecureQuotes($user->id, $quoteLimit, CacheServiceInterface::TEN_SECURE_QUOTES_KEY, $new);
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

    /**
     * Api endpoint /api/report-favorite-quotes
     */
    public function reportFavoriteQuotes(Request $request): \Illuminate\Http\JsonResponse
    {
        $quotes = $this->quoteService->getReportOfFavoriteQuotes();
        return response()->json([
            'quotes' => $quotes
        ], 200);
    }

    /**
     * Api endpoint /api/favorite-quotes/{id}
     * Type: DELETE
     * Description: Delete a quote from favorite quotes for authenticated user
     */
    public function deleteFavoriteQuote(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $this->quoteService->deleteFavoriteQuote($request->user()->id, $id);
        return response()->json([
            'message' => 'Quote deleted successfully'
        ], 200);
    }
}
