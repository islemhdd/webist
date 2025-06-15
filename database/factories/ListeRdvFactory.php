<?php

namespace Database\Factories;

use App\Models\ListeRdv;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListeRdv>
 */
class ListeRdvFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ListeRdv::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = ['Medical General', 'Psychology', 'Dental', 'Specialist', 'Physiotherapy', 'Counseling'];
        $motifs = ['Check-up', 'Treatment', 'Follow-up', 'Consultation', 'Emergency', 'Regular visit'];

        return [
            'matricule' => Student::inRandomOrder()->first()?->matricule,
            'motif' => fake()->randomElement($motifs),
            'service' => fake()->randomElement($services),
            'date' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
