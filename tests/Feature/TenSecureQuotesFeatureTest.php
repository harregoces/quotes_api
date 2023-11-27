<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 9.    Feature: Ten Secure Quotes
 * o    A web page with URI of “/api/secure-quotes” that shows 10 random quotes.
 * o    The web page should display cached information, if available, by default
 *     If cache was used, the quotes should be prefixed with an appropriate icon or “[cached]” keyword/tag.
 * o    There should be a button to force a reload of list of 10 random quotes with a “new” parameter (e.g., /api/secure-quotes/new).
 * o    There should be a button to add each quote to the list of favorites for the logged in user.
 */

class TenSecureQuotesFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/api/secure-quotes” that shows 10 random quotes.
     * The web page should display cached information, if available, by default
     * If cache was used, the quotes should be prefixed with an appropriate icon or “[cached]” keyword/tag.
     */
    public function testSecureQuotes(): void
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

        $token = $response->json()['token'];

        $response = $this->get('/api/secure-quotes',
            [ 'Authorization' => 'Bearer ' . $token, ]
        );
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'quote',
                    'author',
                    'cached'
                ]
            ],
        ]);
        $this->assertFalse($response->json()['quotes'][0]['cached']);

        $response = $this->get('/api/secure-quotes',
            [ 'Authorization' => 'Bearer ' . $token, ]
        );
        $response->assertStatus(200);
        $this->assertTrue($response->json()['quotes'][0]['cached']);

        /**
         * Check if the quotes are not cached after /api/secure-quotes?new=true
         */
        $response = $this->get('/api/secure-quotes?new=true',
            [ 'Authorization' => 'Bearer ' . $token, ]
        );
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'quote',
                    'author',
                    'cached'
                ]
            ],
        ]);
        $this->assertFalse($response->json()['quotes'][0]['cached']);
    }

}
