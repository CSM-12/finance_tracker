<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        category::factory()->create([
            'name' => 'food',
            'budget' => 2000,
            'user_id' => 1,
        ]);

        Transaction::factory()->create([
            'title' => 'lunch',
            'amount' => 100,
            'category_id' => 1,
            'user_id' => 1,
            'type' => 'out'
        ]);
    }
}
