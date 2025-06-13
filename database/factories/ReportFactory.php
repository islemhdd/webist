<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isMedical = fake()->boolean(30); // 30% chance of being medical

        return [
            'student_id' => Student::inRandomOrder()->first()?->matricule,
            'officer_id' => User::where('role', 'officer')->inRandomOrder()->first()?->id,
            'status' => fake()->randomElement(['Chef de compagnie', 'Chef de brigade', 'Chef de batallaint', 'Chef division', 'Medecin', 'Directeur général']),
            'title' => fake()->sentence(),
            'corps' => fake()->paragraphs(2, true),
            'is_medical' => $isMedical,
            'destination' => $isMedical ? fake()->randomElement(['Medical Center', 'Hospital', 'Clinic']) : null,
        ];
    }
}
