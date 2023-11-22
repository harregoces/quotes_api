<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 11.    Feature: Report of Favorite Quotes
 * o    A web page with URI of “/api/report-favorite-quotes” that shows a list of registered users and favorite quotes they have added to their list.
 * o    There should be a button to delete each quote from the list of favorites for the logged in user only. DELETE /favorite-quotes/{id}
 */
class ReportOfFavoriteFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A webpage with URI of “/api/report-favorite-quotes” that shows a list of registered users and favorite quotes they have added to their list.
     */
    public function test_report_of_favorite_quotes(): void
    {
        $response = $this->post('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);
        $token = $response->json()['token'];

        $response = $this->get('/api/report-favorite-quotes',
            [ 'Authorization' => 'Bearer ' . $token, ]
        );
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'quote' => [
                        'id',
                        'quote',
                        'author',
                    ],
                ],
            ],
        ]);
    }
}
