<?php

namespace Database\Factories;

use App\Models\Sanction;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sanction>
 */
class SanctionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sanction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 months', '-1 week');
        $endDate = fake()->dateTimeBetween($startDate, '+2 weeks');

        return [
            'matricule' => Student::inRandomOrder()->first()?->matricule,
            'type' => fake()->randomElement(['Warning', 'Detention', 'Suspension', 'Expulsion', 'Extra duty']),
            'motif' => fake()->sentence(),
            'date_debut' => $startDate,
            'date_fin' => $endDate,
            'observation' => fake()->paragraph(),
        ];
    }
}
