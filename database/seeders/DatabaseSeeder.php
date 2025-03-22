<?php

namespace Database\Seeders;

use App\Models\CarFuel;
use App\Models\CarType;
use App\Models\Image;
use App\Models\Post;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        Student::factory()->count(50)->create();


        Post::factory()->count(50)->create();
        Image::factory()->count(50)->create();
    }
}
