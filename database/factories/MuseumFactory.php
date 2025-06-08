<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Museum>
 */
class MuseumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->company,
            'image' => $this->faker->imageUrl(640, 480, 'museum', true),
            'opening_time' => $this->faker->time(),
            'clossing_time' => $this->faker->time(),
            'latitude' => $this->faker->latitude(10, 90),
            'longitude' => $this->faker->longitude(-180, 180),
            'description' => $this->faker->paragraph,
            'ticket_price' => $this->faker->randomFloat(2, 0, 100),
            'url' => $this->faker->url,
            'number_of_rooms' => $this->faker->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
