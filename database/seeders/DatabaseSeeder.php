<?php

namespace Database\Seeders;

use App\Models\{
    Listing,
    User
};
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

        User::factory()
            ->has(Listing::factory()->count(1))
            ->create([
                'name' => 'Darrin Deal',
                'email' => 'ddeal@wellspring.marketing'
            ]);

        User::factory(50)
            ->has(Listing::factory()->count(1))
            ->create();
    }
}
