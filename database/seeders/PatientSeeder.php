<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a subset of students
        $students = Student::inRandomOrder()->limit(30)->get();

        $medecin_types = ['General', 'Specialist', 'Dentist', 'Psychiatrist', 'Orthopedist'];

        foreach ($students as $student) {
            $valider = rand(0, 2); // 0: Not validated, 1: Validated, 2: Deleted

            $validated_at = null;
            if ($valider === 1) {
                $validated_at = Carbon::now()->subDays(rand(1, 30));
            }

            $motif_suppression = null;
            if ($valider === 2) {
                $motif_suppression = fake()->sentence();
            }

            Patient::create([
                'matricule' => $student->matricule,
                'valider' => $valider,
                'validated_at' => $validated_at,
                'motif_suppression' => $motif_suppression,
                'type_medecin' => $valider === 2 ? null : $medecin_types[array_rand($medecin_types)],
                'avis_medecin' => $valider === 2 ? null : fake()->paragraph(),
            ]);
        }
    }
}
