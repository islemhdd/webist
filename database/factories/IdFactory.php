<?php

namespace Database\Factories;

use App\Models\Id;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Id>
 */
class IdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Id::class;
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(1, 1000) // Ensure unique integer ID//
        ];
    }
}
