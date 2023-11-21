<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 8.    Feature: Five Random Quotes
 * o    A web page with URI of “/api/quotes” that shows 5 random quotes.
 * o    The web page should display cached information, if available, by default
 *     If cache was used, the quotes should be prefixed with an appropriate icon or “[cached]” keyword/tag.
 * o    There should be a button to force a reload of list of 5 random quotes with a “new” parameter (e.g., /api/quotes/new).
 *     The reload operation updates the cache.
 */

class FiveRandomQuotesFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/api/quotes” that shows 5 random quotes.
     */
    public function test_a_web_page_with_uri_of_quotes_that_shows_5_random_quotes(): void
    {
        $response = $this->get('/api/quotes');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'quote',
                    'author',
                ],
            ],
        ]);
    }

    /**
     * The web page should display cached information, if available, by default
     *     If cache was used, the quotes should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_the_web_page_should_display_cached_information_if_available_by_default(): void
    {
        $response = $this->get('/api/quotes');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'quote',
                    'author',
                    'cached'
                ],
            ],
        ]);
        $this->assertFalse($response->json()['quotes'][0]['cached']);

        $response = $this->get('/api/quotes');
        $response->assertStatus(200);

        $this->assertTrue($response->json()['quotes'][0]['cached']);
    }

    /**
     * There should be a button to force a reload of list of 5 random quotes with a “new” parameter (e.g., /api/quotes/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_list_of_5_random_quotes_with_a_new_parameter(): void
    {
        $response = $this->get('/api/quotes');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'quote',
                    'author',
                    'cached'
                ],
            ],
        ]);
        $this->assertFalse($response->json()['quotes'][0]['cached']);

        $response = $this->get('/api/quotes/new');
        $response->assertStatus(200);

        $this->assertFalse($response->json()['quotes'][0]['cached']);
    }

}
