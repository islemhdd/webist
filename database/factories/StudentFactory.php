<?php

namespace Database\Factories;

use App\Models\Section;
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
            "name" => fake()->word(),
            "grade" => fake()->randomElement([1, 2, 3]),
            "password" => Hash::make('usser'), // You don't need to randomize password
            "deleted_at" => null,
            "consigned" => fake()->randomElement([0, 1]),
            "section_id" => Section::inRandomOrder()->first()?->id ?? 1, // Add this
        ];
    }
}
