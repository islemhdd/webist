<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $matricules = [
            '2022055',
            '2022104',
            '2022011',
            '2022056',
            '2022064',
            '2022120',
            '2022131',
            '2022250',
            '2022002',
            '2022003',
            '2022004',
            '2022005',
            '2022006',
            '2022007',
            '2022096'
        ];

        $types = ['médecin générale', 'dentiste', 'psycho'];

        return [
            'matricule' => $this->faker->randomElement($matricules),
            'valider' => $this->faker->boolean(80), // 80% chance of being validated
            'valider_rhp' => function (array $attributes) {
                return $attributes['valider'] ? $this->faker->boolean(70) : 0;
            },
            'validated_at' => function (array $attributes) {
                return $attributes['valider'] ? $this->faker->dateTimeThisYear() : null;
            },
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear(),
            'motif_suppression' => $this->faker->optional(0.2)->text(200), // 20% chance of having a suppression reason
            'type_medecin' => $this->faker->randomElement($types),
            'avis_medecin' => $this->faker->optional(0.9)->text(500), // 90% chance of having a doctor's opinion
        ];
    }
}
