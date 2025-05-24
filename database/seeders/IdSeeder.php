<?php

namespace Database\Seeders;

use App\Models\Id;
use Illuminate\Database\Console\Seeds\WithoutModStudentnts;
use Illuminate\Database\Seeder;

class IdSeeder extends Seeder
{

    public function run(): void
    {
        Id::factory()->count(10000)->create();
    }
}
