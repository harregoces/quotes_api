<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuotesFavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get 3 created users by the seeder and attach 3 quotes to each user
        $users = \App\Models\User::all();
        $quotes = \App\Models\Quotes::all();

        foreach ($users as $user) {
            $user->favoriteQuotes()->attach($quotes->random(3));
        }

    }
}
