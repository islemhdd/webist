<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $valider = fake()->randomElement([0, 1, 2]); // 0: Not validated, 1: Validated, 2: Deleted
        $medecin_types = ['General', 'Specialist', 'Dentist', 'Psychiatrist', 'Orthopedist'];

        $validated_at = null;
        if ($valider === 1) {
            $validated_at = fake()->dateTimeBetween('-30 days', 'now');
        }

        $motif_suppression = null;
        if ($valider === 2) {
            $motif_suppression = fake()->sentence();
        }

        return [
            'matricule' => Student::inRandomOrder()->first()?->matricule,
            'valider' => $valider,
            'validated_at' => $validated_at,
            'motif_suppression' => $motif_suppression,
            'type_medecin' => $valider === 2 ? null : fake()->randomElement($medecin_types),
            'avis_medecin' => $valider === 2 ? null : fake()->paragraph(),
        ];
    }
}
