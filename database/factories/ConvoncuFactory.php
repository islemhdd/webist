<?php

namespace Database\Factories;

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Convoncu>
 */
class ConvoncuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Convoncu::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matricule' => Student::inRandomOrder()->first()?->matricule,
            'psy' => fake()->boolean(),
            'medGen' => fake()->boolean(),
            'chirDent' => fake()->boolean(),
            'avisSpe' => fake()->boolean(),
        ];
    }
}
