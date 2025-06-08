<?php

namespace Database\Seeders;

use App\Models\Museum;
use App\Models\Room;
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
            'name' => 'Vera',
            'email' => 'geovanni@example.com',
        ]);

        Museum::factory(10)->create([
            'status' => 'active',
        ]);

        Room::factory(50)->create();


    }
}
