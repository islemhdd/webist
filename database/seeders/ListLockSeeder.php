<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locks = [
            ['id' => 1, 'status' => 0],
            ['id' => 2, 'status' => 0],
            ['id' => 3, 'status' => 0],
        ];

        foreach ($locks as $lock) {
            DB::table('list_lock')->insert($lock);
        }
    }
}
