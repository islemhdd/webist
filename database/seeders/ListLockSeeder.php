<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - list_lock table
     */
    public function run(): void
    {
        $listLocks = [
            ['id' => 1, 'status' => 0],
            ['id' => 2, 'status' => 0],
            ['id' => 3, 'status' => 0],
        ];

        foreach ($listLocks as $lock) {
            DB::table('list_lock')->updateOrInsert(
                ['id' => $lock['id']],
                $lock
            );
        }
    }
}
