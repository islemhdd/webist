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
            ReportSeeder::class,
            SanctionSeeder::class,
            NotificationSeeder::class,
            ListLockSeeder::class,
        ]);
    }
}
