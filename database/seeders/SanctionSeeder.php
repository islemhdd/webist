<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - sanctions table
     */
    public function run(): void
    {
        $sanctions = [
            [
                'id' => 1,
                'matricule' => 2022003,
                'type' => 'arret',
                'motif' => 'sdlcjskldv',
                'date_debut' => '2025-12-04',
                'date_fin' => '2026-01-04',
                'created_at' => '2025-12-29 17:19:55',
                'updated_at' => '2025-12-29 17:19:55',
                'report_id' => 28
            ],
            [
                'id' => 2,
                'matricule' => 2022003,
                'type' => 'arret',
                'motif' => 'hizedilqzehrfioqehrfoiqhzm',
                'date_debut' => '2026-01-10',
                'date_fin' => '2026-01-11',
                'created_at' => '2025-12-30 02:28:04',
                'updated_at' => '2025-12-30 02:28:04',
                'report_id' => 28
            ],
            [
                'id' => 3,
                'matricule' => 2022005,
                'type' => 'arret',
                'motif' => '1234567890',
                'date_debut' => '2025-12-31',
                'date_fin' => '2026-01-11',
                'created_at' => '2025-12-30 02:43:51',
                'updated_at' => '2025-12-30 02:43:51',
                'report_id' => 29
            ],
        ];

        foreach ($sanctions as $sanction) {
            DB::table('sanctions')->updateOrInsert(
                ['id' => $sanction['id']],
                $sanction
            );
        }
    }
}
