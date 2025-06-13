<?php

namespace Database\Seeders;

use App\Models\ListeRdv;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ListeRdvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a subset of students
        $students = Student::inRandomOrder()->limit(25)->get();

        $services = ['Medical General', 'Psychology', 'Dental', 'Specialist', 'Physiotherapy', 'Counseling'];
        $motifs = ['Check-up', 'Treatment', 'Follow-up', 'Consultation', 'Emergency', 'Regular visit'];

        foreach ($students as $student) {
            // Create 1-3 appointments per selected student
            $appointmentCount = rand(1, 3);

            for ($i = 0; $i < $appointmentCount; $i++) {
                // Random date in the next 30 days
                $date = Carbon::now()->addDays(rand(1, 30))->addHours(rand(8, 17));

                ListeRdv::create([
                    'matricule' => $student->matricule,
                    'motif' => $motifs[array_rand($motifs)],
                    'service' => $services[array_rand($services)],
                    'date' => $date,
                ]);
            }
        }
    }
}
