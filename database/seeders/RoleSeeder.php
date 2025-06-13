<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            ['name' => 'Chef de compagnie', 'description' => 'Company leader'],
            ['name' => 'Chef de brigade', 'description' => 'Brigade leader'],
            ['name' => 'Chef de batallaint', 'description' => 'Battalion leader'],
            ['name' => 'Chef division', 'description' => 'Division leader'],
            ['name' => 'Medecin', 'description' => 'Medical doctor'],
            ['name' => 'Directeur général', 'description' => 'General director']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
