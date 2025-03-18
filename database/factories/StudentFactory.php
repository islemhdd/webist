<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => fake()->unique()->numberBetween(1, 999),
            "name" => fake()->word(),
            "grade" => fake()->randomElement([1, 2, 3]),
            "password" => Hash::make(fake()->word("usser")),
            "deleted_at" => null,
            "price" => fake()->numberBetween(200, 1000),
            "pac" => fake()->numerify("#######")
        ];
    }
}
