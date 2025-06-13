<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all sections
        $sections = Section::all();

        // For each section, create some students
        foreach ($sections as $section) {
            // Create 8-15 students per section
            $studentCount = rand(8, 15);

            for ($i = 0; $i < $studentCount; $i++) {
                // Generate a unique matricule based on section and counter
                $matricule = $section->code() . str_pad($i + 1, 3, '0', STR_PAD_LEFT);

                Student::create([
                    'matricule' => $matricule,
                    'nom' => fake()->lastName(),
                    'prenom' => fake()->firstName(),
                    'grade' => $section->bat,
                    'section_id' => $section->id,
                    'consigned' => fake()->boolean(20), // 20% chance of being consigned
                    'choix' => null,
                ]);
            }
        }
    }
}
