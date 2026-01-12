<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - sections table
     */
    public function run(): void
    {
        $sections = [
            ['id' => 321, 'bat' => 3, 'companie' => 2, 'num' => 1, 'officer_id' => 1, 'created_at' => '2025-05-22 19:18:07', 'updated_at' => '2025-05-22 19:18:07'],
            ['id' => 341, 'bat' => 3, 'companie' => 4, 'num' => 1, 'officer_id' => 8, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 342, 'bat' => 3, 'companie' => 4, 'num' => 2, 'officer_id' => 8, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 343, 'bat' => 3, 'companie' => 4, 'num' => 3, 'officer_id' => 8, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 351, 'bat' => 3, 'companie' => 5, 'num' => 1, 'officer_id' => 1, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 352, 'bat' => 3, 'companie' => 5, 'num' => 2, 'officer_id' => 1, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 353, 'bat' => 3, 'companie' => 5, 'num' => 3, 'officer_id' => 1, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 361, 'bat' => 3, 'companie' => 6, 'num' => 1, 'officer_id' => 1, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
            ['id' => 362, 'bat' => 3, 'companie' => 6, 'num' => 2, 'officer_id' => 1, 'created_at' => '2025-03-20 02:50:30', 'updated_at' => '2025-03-20 02:50:48'],
        ];

        // Use DB::table to bypass triggers and insert with specific IDs
        foreach ($sections as $section) {
            DB::table('sections')->updateOrInsert(
                ['id' => $section['id']],
                $section
            );
        }
    }
}
