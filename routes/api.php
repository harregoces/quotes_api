
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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

/**
 * Api endpoint /api/today/new
 * Type: GET
 * Description: clear cache and returns a quote for non-authenticated user
 */
Route::get('/today/new', 'App\Http\Controllers\QuoteController@newToday');

/**
 * Api endpoint /api/secure-quotes
 * Type: GET
 * Description: Returns quotes for authenticated user
 */
Route::get('/secure-quotes', 'App\Http\Controllers\QuoteController@secureQuotes')->middleware('auth:sanctum');

/**
 * Api endpoint /api/secure-quotes/new
 *  Type: GET
 *  Description: clear cache and returns quotes for authenticated user
 */
Route::get('/secure-quotes/new', 'App\Http\Controllers\QuoteController@newSecureQuotes')->middleware('auth:sanctum');

/**
 * Api endpoint /api/quotes
 * Type: GET
 * Description: Returns five random quotes for non-authenticated user and authenticated user
 */
Route::get('/quotes', 'App\Http\Controllers\QuoteController@quotes');

/**
 * Api endpoint /api/quotes/new
 * Type: GET
 * Description: clear cache and returns five random quotes for non-authenticated user and authenticated user
 */
Route::get('/quotes/new', 'App\Http\Controllers\QuoteController@newQuotes');

/**
 * Api endpoint /favorite-quotes
 * Type: GET
 * Description: Returns favorite quotes for authenticated user
 */
Route::get('/favorite-quotes', 'App\Http\Controllers\QuoteController@favoriteQuotes')->middleware('auth:sanctum');

/**
 * Api endpoint /report-favorite-quotes
 * Type: GET
 * Description: Returns a report of the favorite quotes
 */
Route::get('/report-favorite-quotes', 'App\Http\Controllers\QuoteController@favoriteQuotes')->middleware('auth:sanctum');

/**
 * Api endpoint /api/quotes/{id}/favorite
 * Type: POST
 * Description: Marks a quote as favorite for authenticated user
 */
Route::post('/quotes/{id}/favorite', 'App\Http\Controllers\QuoteController@saveFavorite')->middleware('auth:sanctum');

/**
 * Api endpoint /api/quotes/{id}/favorite
 * Type: DELETE
 * Description: Marks a quote as favorite for authenticated user
 */
Route::delete('/quotes/{id}/favorite', 'App\Http\Controllers\QuoteController@deleteFavorite')->middleware('auth:sanctum');


