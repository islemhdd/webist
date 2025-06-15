<?php

namespace Database\Factories;

use App\Models\Exemption;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exemption>
 */
class ExemptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exemption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', 'now');
        $endDate = fake()->dateTimeBetween($startDate, '+2 weeks');

        $motifs = [
            'Medical reason: Injury',
            'Medical reason: Illness',
            'Family emergency',
            'Academic competition',
            'Sports competition',
            'Personal reasons'
        ];

        return [
            'matricule' => Student::inRandomOrder()->first()?->matricule,
            'motif' => fake()->randomElement($motifs),
            'date_debut' => $startDate,
            'date_fin' => $endDate,
        ];
    }
}
