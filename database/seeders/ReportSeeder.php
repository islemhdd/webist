<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - reports table
     */
    public function run(): void
    {
        $reports = [
            [
                'id' => 20,
                'student_id' => 2022003,
                'officer_id' => 1,
                'status' => 'Chef de batallaint',
                'title' => 'dcsdc',
                'corps' => 'dcoisjd',
                'is_medical' => 0,
                'destination' => 3,
                'AvisChef_de_compagnie' => null,
                'AvisChef_de_batallaint' => null,
                'AvisChef_de_brigade' => null,
                'AvisDirecteur_général' => null,
                'AvisChef_division' => null,
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-05-30 09:46:37',
                'updated_at' => '2025-05-30 09:46:39',
                'deleted_at' => null,
                'arret' => 0
            ],
            [
                'id' => 21,
                'student_id' => 2022004,
                'officer_id' => 1,
                'status' => 'Chef de batallaint',
                'title' => 'jhbkjh',
                'corps' => 'jklbjhklkkhgvj',
                'is_medical' => 0,
                'destination' => 3,
                'AvisChef_de_compagnie' => null,
                'AvisChef_de_batallaint' => null,
                'AvisChef_de_brigade' => null,
                'AvisDirecteur_général' => null,
                'AvisChef_division' => null,
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-05-30 12:54:55',
                'updated_at' => '2025-05-30 12:54:56',
                'deleted_at' => null,
                'arret' => 0
            ],
            [
                'id' => 22,
                'student_id' => 2022004,
                'officer_id' => 1,
                'status' => 'DONE',
                'title' => 'miohbj,',
                'corps' => 'jhkvbhbgh ',
                'is_medical' => 0,
                'destination' => 1,
                'AvisChef_de_compagnie' => 'bbbbbbb',
                'AvisChef_de_batallaint' => 'zelfhsdcfvze',
                'AvisChef_de_brigade' => 'dkjcnslqdc sd',
                'AvisDirecteur_général' => 'ui<sjklcn<sdklc<s',
                'AvisChef_division' => 'KKKKKKKKKKKKKK',
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-05-30 12:55:12',
                'updated_at' => '2025-05-30 13:58:11',
                'deleted_at' => null,
                'arret' => 0
            ],
            [
                'id' => 23,
                'student_id' => 2022002,
                'officer_id' => 1,
                'status' => 'Chef de batallaint',
                'title' => 'ksdlcn',
                'corps' => 'ZeofdjZEMOFNEQR',
                'is_medical' => 0,
                'destination' => 3,
                'AvisChef_de_compagnie' => 'LFNVDMFVNQVFS',
                'AvisChef_de_batallaint' => null,
                'AvisChef_de_brigade' => null,
                'AvisDirecteur_général' => null,
                'AvisChef_division' => null,
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-07-17 20:56:09',
                'updated_at' => '2025-07-17 20:56:31',
                'deleted_at' => null,
                'arret' => 0
            ],
            [
                'id' => 27,
                'student_id' => 2022002,
                'officer_id' => 1,
                'status' => 'Chef de batallaint',
                'title' => 'DJDJD',
                'corps' => 'SDFIOQEJHFILQER',
                'is_medical' => 0,
                'destination' => 3,
                'AvisChef_de_compagnie' => null,
                'AvisChef_de_batallaint' => null,
                'AvisChef_de_brigade' => null,
                'AvisDirecteur_général' => null,
                'AvisChef_division' => null,
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-12-29 13:46:47',
                'updated_at' => '2025-12-29 13:46:47',
                'deleted_at' => null,
                'arret' => 0
            ],
            [
                'id' => 28,
                'student_id' => 2022003,
                'officer_id' => 9,
                'status' => 'DONE',
                'title' => 'SFK/JNER',
                'corps' => 'SDFVJKLQSDHFLKAERF',
                'is_medical' => 0,
                'destination' => 9,
                'AvisChef_de_compagnie' => null,
                'AvisChef_de_batallaint' => null,
                'AvisChef_de_brigade' => null,
                'AvisDirecteur_général' => 'arret de2026-01-10 au 2026-01-11',
                'AvisChef_division' => null,
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-12-29 13:49:44',
                'updated_at' => '2025-12-30 02:28:00',
                'deleted_at' => null,
                'arret' => 1
            ],
            [
                'id' => 29,
                'student_id' => 2022005,
                'officer_id' => 1,
                'status' => 'DONE',
                'title' => 'hiii3',
                'corps' => 'Directeur généralDirecteur généralDirecteur généralDirecteur généralDirecteur généralDirecteur',
                'is_medical' => 0,
                'destination' => 1,
                'AvisChef_de_compagnie' => 'ihjihihojhkh',
                'AvisChef_de_batallaint' => 'azertyuiop',
                'AvisChef_de_brigade' => '123456789',
                'AvisDirecteur_général' => 'arret de2025-12-31 au 2026-01-11',
                'AvisChef_division' => '231234567890',
                'motif' => null,
                'refused' => 0,
                'created_at' => '2025-12-30 02:39:42',
                'updated_at' => '2025-12-30 02:43:51',
                'deleted_at' => null,
                'arret' => 1
            ],
        ];

        foreach ($reports as $report) {
            DB::table('reports')->updateOrInsert(
                ['id' => $report['id']],
                $report
            );
        }
    }
}
