<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/**
 * Console command: php artisan Get-FiveRandomQuotes --new
 * Description: Returns five random quotes
 */
Artisan::command('Get-FiveRandomQuotes {--new}',
    // call the function getFiveRandomQuotes from QuoteController
    function () {
        $this->comment(app('App\Http\Controllers\QuoteController')->getFiveRandomQuotes($this->option('new')));
    }
)->purpose('Returns five random quotes');
