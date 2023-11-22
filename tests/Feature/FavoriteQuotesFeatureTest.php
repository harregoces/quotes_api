<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 10.    Feature: Favorite Quotes
 * o    A web page with URI of “/api/favorite-quotes” that shows all quotes that have been added to the list of favorites.
 * o    There should be a button to delete each quote from the list of favorites.
 * o    The page is accessible to authenticated/logged in users only.
 * o    If the list of favorite quotes is empty, a message should be shown and suggest to the user how to add quotes to the list.
 */
class FavoriteQuotesFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A web page with URI of “/api/favorite-quotes” that shows all quotes that have been added to the list of favorites.
     * There should be a button to delete each quote from the list of favorites.
     * The page is accessible to authenticated/logged-in users only.
     * If the list of favorite quotes is empty, a message should be shown and suggest to the user how to add quotes to the list.
     */
    public function testFavoriteQuotes(): void
    {
        $name = 'test favorite quotes';
        $email = 'testfavorite@test.com';
        $password = 'password';

        $this->post('/api/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response = $this->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);

        $token = $response->json()['token'];
        $user = $response->json()['user'];

        $this->post('/api/favorite-quotes', [
            'quote' => [
                'quote' => 'test quote',
                'author' => 'test author',
            ],
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $this->assertDatabaseHas('quotes', [
            'quote' => 'test quote',
            'author' => 'test author',
        ]);

        $this->assertDatabaseHas('quotes_favorites', [
            'user_id' => $user['id'],
        ]);

        $response = $this->get('/api/favorite-quotes', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'id',
                    'quote',
                    'author',
                ],
            ],
        ]);

        $this->assertEquals('test quote', $response->json()['quotes'][0]['quote']);

        $quoteId = $response->json()['quotes'][0]['id'];
        $response = $this->get('/api/favorite-quotes/' . $quoteId,
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'quote' => [
                'id',
                'quote',
                'author',
            ],
        ]);

        /**
         * test delete favorite quote
         */
        $response = $this->delete('/api/favorite-quotes/' . $quoteId,
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
        ]);

        $this->assertDatabaseMissing('quotes_favorites', [
            'user_id' => $user['id'],
            'quote_id' => $quoteId,
        ]);

    }
}
