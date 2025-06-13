<?php

namespace Database\Seeders;

use App\Models\Convoncu;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ConvoncuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a subset of students
        $students = Student::inRandomOrder()->limit(20)->get();

        foreach ($students as $student) {
            Convoncu::create([
                'matricule' => $student->matricule,
                'psy' => fake()->boolean(),
                'medGen' => fake()->boolean(),
                'chirDent' => fake()->boolean(),
                'avisSpe' => fake()->boolean(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
