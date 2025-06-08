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
            'email' => 'geovanni.vera23@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Demian',
            'email' => 'obeddemian@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Tapia',
            'email' => 'josetapia121205@gmail.com',
        ]);

    }
}
