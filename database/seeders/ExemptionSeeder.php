<?php

namespace Database\Seeders;

use App\Models\Exemption;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExemptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a subset of students
        $students = Student::inRandomOrder()->limit(15)->get();

        $motifs = [
            'Medical reason: Injury',
            'Medical reason: Illness',
            'Family emergency',
            'Academic competition',
            'Sports competition',
            'Personal reasons'
        ];

        foreach ($students as $student) {
            // Create 1-2 exemptions per selected student
            $exemptionCount = rand(1, 2);

            for ($i = 0; $i < $exemptionCount; $i++) {
                $startDate = Carbon::now()->subDays(rand(1, 30));
                $endDate = (clone $startDate)->addDays(rand(1, 7));

                Exemption::create([
                    'matricule' => $student->matricule,
                    'motif' => $motifs[array_rand($motifs)],
                    'date_debut' => $startDate,
                    'date_fin' => $endDate,
                ]);
            }
        }
    }
}
