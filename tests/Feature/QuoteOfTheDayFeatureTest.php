<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 7.    Feature: Quote of the Day
 * o    A web page with URI of “/api/today” that shows “quote of the day”.
 * o    The web page should display cached information, if available, by default
 *     If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
 * o    There should be a button to force a reload of the “quote of the day” with a “new” parameter (e.g., /api/today/new).
 * o    There should be a button to add the “quote of the day” to the list of favorites.
 * o    Default page when accessing “/” URI.
 * o    The page is accessible to unauthenticated users.
 * o    The page is accessible to authenticated/logged-in users.
 */
class QuoteOfTheDayFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/api/today” that shows “quote of the day”.
     */
    public function test_a_web_page_with_uri_of_today_that_shows_quote_of_the_day(): void
    {
        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
            ],
        ]);
    }

    /**
     * The web page should display cached information, if available, by default
     *     If cache was used, the quote should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function test_the_web_page_should_display_cached_information_if_available_by_default(): void
    {
        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
                'cached'
            ],
        ]);
        $this->assertFalse($response->json()['quote']['cached']);

        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
                'cached'
            ],
        ]);
        $this->assertTrue($response->json()['quote']['cached']);
    }

    /**
     * There should be a button to force a reload of the “quote of the day” with a “new” parameter (e.g., /today/new).
     */
    public function test_there_should_be_a_button_to_force_a_reload_of_the_quote_of_the_day_with_a_new_parameter(): void
    {
        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
                'cached'
            ],
        ]);
        $this->assertFalse($response->json()['quote']['cached']);

        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
                'cached'
            ],
        ]);
        $this->assertTrue($response->json()['quote']['cached']);

        $response = $this->get('/api/today?new=true');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
                'cached'
            ],
        ]);
        $this->assertFalse($response->json()['quote']['cached']);

    }

    /**
     * There should be a button to add the “quote of the day” to the list of favorites.
     * 1 authenticate the user
     * 2 get the quote of the day
     * 3 add the quote of the day to the list of favorites
     */
    public function test_there_should_be_a_button_to_add_the_quote_of_the_day_to_the_list_of_favorites(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);
        $userId = $response->json()['user']['id'];
        $token = $response->json()['token'];

        $response = $this->get('/api/today');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quote' => [
                'quote',
                'author',
            ],
        ]);

        $quote = $response->json()['quote'];
        $response = $this->post('/api/favorite-quotes',
            [ 'quote' => $quote, ],
            [ 'Authorization' => 'Bearer ' . $token,]
        );

        $response->assertStatus(200);
        $quoteId =  $response->json()['quote']['id'];

        $this->assertDatabaseHas('quotes_favorites', [
            'user_id' => $userId,
            'quote_id' => $quoteId,
        ]);

    }
}
