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
     * The page is accessible to authenticated/logged in users only.
     * If the list of favorite quotes is empty, a message should be shown and suggest to the user how to add quotes to the list.
     */
    public function testFavoriteQuotes(): void
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

        // TODO: Add favorite quotes
    }
}
