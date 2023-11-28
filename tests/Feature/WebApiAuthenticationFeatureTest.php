<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 5.    Feature: Web/API Authentication
 * o    Users can log in with URI “/api/login” using username and password.
 * o    The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
 * o    The page allows for currently authenticated users to switch to another authenticated user (with correct credentials).
 * o    The login & logout process does not delete the list of favorites for a previously logged-in user.
 */

class WebApiAuthenticationFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can log in with URI “/login” using username and password.
     */
    public function test_users_can_login_with_uri_login_using_username_and_password(): void
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
    }

    /**
     * The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
     */
    public function test_the_username_is_in_the_form_of_a_proper_email_address_containing_only_alphanumeric_characters_plus_at_sign_and_dot(): void
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

        $this->post('/api/logout');

        $response = $this->post('/api/login', [
            'email' => 'test@test',
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'message',
        ]);

        $response = $this->post('/api/login', [
            'email' => 'testtest.com',
            'password' => 'password',
        ]);
        $response->assertJsonStructure([
            'message',
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@test..com',
            'password' => 'password',
        ]);
        $response->assertJsonStructure([
            'message',
        ]);

        $this->assertGuest();

        $response = $this->post('/api/login', [
            'email' => 'test@test@.com',
            'password' => 'password',
        ]);
        $response->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * The page allows for currently authenticated users to switch to another authenticated user (with correct credentials).
     */
    public function test_the_page_allows_for_currently_authenticated_users_to_switch_to_another_authenticated_user_with_correct_credentials(): void
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

        $response = $this->post('/api/login', [
            'email' => 'test2@test.com',
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

    }

    /**
     * The login & logout process does not delete the list of favorites for a previously logged-in user.
     */
    public function test_the_login_and_logout_process_does_not_delete_the_list_of_favorites_for_a_previously_logged_in_user(): void
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
        $response = $this->get('/api/favorite-quotes',
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );
        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'id',
                    'quote',
                    'author'
                ],
            ],
        ]);

        $response = $this->post('/api/logout',
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        );
        $response->assertStatus(200);

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

        $response = $this->get('/api/favorite-quotes',
            [
                'Authorization' => 'Bearer ' . $response->json()['token'],
            ]
        );
        $response->assertJsonStructure([
            'quotes' => [
                '*' => [
                    'id',
                    'quote',
                    'author'
                ],
            ],
        ]);

    }

}
