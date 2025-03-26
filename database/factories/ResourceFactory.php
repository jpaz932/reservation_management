<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => Resource::TYPES['MEETING_ROOM'],
            'description' => $this->faker->sentence(),
            'capacity' => $this->faker->numberBetween(1, 100),
            'is_active' => 1,
        ];
    }
}
