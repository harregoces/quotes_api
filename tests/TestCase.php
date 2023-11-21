<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UsersTableSeeder::class);
        $this->seed(\Database\Seeders\QuotesTableSeeder::class);
        $this->seed(\Database\Seeders\QuotesFavoritesTableSeeder::class);
    }

}
