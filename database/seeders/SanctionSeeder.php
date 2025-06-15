<?php

namespace Database\Seeders;

use App\Models\Sanction;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SanctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a subset of students
        $students = Student::inRandomOrder()->limit(20)->get();

        foreach ($students as $student) {
            // Create 1-3 sanctions per selected student
            $sanctionCount = rand(1, 3);

            for ($i = 0; $i < $sanctionCount; $i++) {
                $startDate = Carbon::now()->subDays(rand(1, 60));
                $endDate = (clone $startDate)->addDays(rand(1, 14));

                Sanction::create([
                    'matricule' => $student->matricule,
                    'motif' => fake()->sentence(),
                    'date_debut' => $startDate,
                    'date_fin' => $endDate,
                ]);
            }
        }
    }
}
