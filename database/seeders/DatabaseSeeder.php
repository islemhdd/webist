<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SectionSeeder::class,
            StudentSeeder::class,
            SanctionSeeder::class,
            ExemptionSeeder::class,
            ConvoncuSeeder::class,
            ListeRdvSeeder::class,
            PatientSeeder::class,
            ListLockSeeder::class,
        ]);
    }
}
