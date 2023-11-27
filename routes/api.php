
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'throttle:5,30'])->group(function () {
    /**
     * Api endpoint /api/favorite-quotes
     * Type: GET
     * Description: Returns favorite quotes for authenticated user
     */
    Route::get('/favorite-quotes', 'App\Http\Controllers\QuoteController@favoriteQuotes');

    /**
     * Api endpoint /api/favorite-quotes
     * Type: POST
     * Description: Save a quote as favorite for authenticated user
     */
    Route::post('/favorite-quotes', 'App\Http\Controllers\QuoteController@saveFavoriteQuotes');

    /**
     * Api endpoint /api/favorite-quotes/{id}
     * Type: GET
     * Description: Get specific favorite quote for authenticated user
     */
    Route::get('/favorite-quotes/{id}', 'App\Http\Controllers\QuoteController@getFavoriteQuote');

    /**
     * Api endpoint /api/favorite-quotes/{id}
     * Type: DELETE
     * Description: Delete specific favorite quote for authenticated user
     */
    Route::delete('/favorite-quotes/{id}', 'App\Http\Controllers\QuoteController@deleteFavoriteQuote');

    /**
     * Api endpoint /api/secure-quotes
     * Type: GET
     * Description: Returns quotes for authenticated user
     */
    Route::get('/secure-quotes', 'App\Http\Controllers\QuoteController@secureQuotes');

    /**
     * Api endpoint /api/report-favorite-quotes
     * Type: GET
     * Description: Returns a report of the all user and the favorite quotes
     */
    Route::get('/report-favorite-quotes', 'App\Http\Controllers\QuoteController@reportFavoriteQuotes');

    /**
     * Api endpoint /api/report-favorite-quotes
     * Type: GET
     * Description: Returns a report of the all user and the favorite quotes
     */
    Route::get('/report-favorite-quotes', 'App\Http\Controllers\QuoteController@reportFavoriteQuotes');

});

Route::middleware('throttle:5,30')->group(function () {

    /**
     * Api endpoint /api/login
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    Route::post('login', 'App\Http\Controllers\UserController@login')->name('login');

    /**
     * Api endpoint /api/logout
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    Route::post('logout', 'App\Http\Controllers\UserController@logout')->middleware('auth:sanctum');

    /**
     * Api endpoint /api/register
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    Route::post('/register', 'App\Http\Controllers\UserController@register')->name('register');

    /**
     * Api endpoint /api/today
     * Type: GET
     * Description: Returns a quote for non-authenticated user
     */
    Route::get('/today', 'App\Http\Controllers\QuoteController@today');
});
