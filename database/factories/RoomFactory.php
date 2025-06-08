<?php

namespace Database\Factories;

use App\Models\Museum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'image' => $this->faker->imageUrl(640, 480, 'museum', true),
            'description' => $this->faker->paragraph,
            'museum_id' => Museum::all()->random()->id, 
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
