<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


/**
 * 3.   Feature: Datastore Initialization
 * o    Datastore should be initialized with 3 users.
 * o    Datastore should be initialized with a list containing 3 favorite quotes for each seeded user.
 */

class DatastoreInitializationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Datastore should be initialized with 3 users.
     */
    public function test_datastore_should_be_initialized_with_3_users(): void
    {
        $users = \App\Models\User::all();

        $this->assertCount(3, $users);
    }

    /**
     * Datastore should be initialized with a list containing 3 favorite quotes for each seeded user.
     */
    public function test_datastore_should_be_initialized_with_a_list_containing_3_favorite_quotes_for_each_seeded_user(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $this->assertCount(3, $user->favoriteQuotes);
        }
    }

}
