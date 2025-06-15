<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [

            ['bat' => 3, 'companie' => 5, 'num' => 1, 'officer_id' => 2],
            ['bat' => 3, 'companie' => 5, 'num' => 2, 'officer_id' => 2],
            ['bat' => 3, 'companie' => 5, 'num' => 3, 'officer_id' => 2],
            ['bat' => 3, 'companie' => 6, 'num' => 1, 'officer_id' => 2],
            ['bat' => 3, 'companie' => 6, 'num' => 2, 'officer_id' => 2],

        ]; //Get officer users to assign to sections

        //for copilot : is it ok that sections variable is a table ?
        foreach ($sections as $section)
            Section::create($section);
    }
}
