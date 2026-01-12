<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - roles table
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Chef de compagnie', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
            ['id' => 2, 'name' => 'Chef de batallaint', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
            ['id' => 3, 'name' => 'Chef de brigade', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
            ['id' => 4, 'name' => 'Chef division', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
            ['id' => 5, 'name' => 'Medecin', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
            ['id' => 6, 'name' => 'Directeur général', 'description' => null, 'created_at' => '2025-03-19 12:24:41', 'updated_at' => '2025-03-19 12:24:41'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
