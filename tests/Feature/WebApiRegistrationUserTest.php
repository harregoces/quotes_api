<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * 6.    Feature: Web Registration for Users
 * o    The application supports user online registration at URI “/api/register”.
 * o    The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
 * o    Password must not be stored in clear text format.
 */
class WebApiRegistrationUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The application supports user online registration at URI “/api/register”.
     */
    public function test_the_application_supports_user_online_registration_at_uri_api_register(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
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

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test1@test.com',
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $response->json('user.id'),
        ]);

    }

    /**
     * The username is in the form of a proper email address containing only alphanumeric characters plus at-sign (@), and dot (.).
     */
    public function test_the_username_is_in_the_form_of_a_proper_email_address_containing_only_alphanumeric_characters_plus_at_sign_and_dot(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test%test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
            'email' => 'test&@test.com',
        ]);

        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
            'email' => 'test@test',
        ]);

        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testtest.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);

    }

    /**
     * Password must not be stored in clear text format.
     */
    public function test_password_must_not_be_stored_in_clear_text_format(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test1@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
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

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test1@test.com',
        ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $response->json('user.id'),
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Test User',
            'email' => 'test1@test.com',
            'password' => 'password',
        ]);

    }



}
