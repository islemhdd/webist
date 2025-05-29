<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DEUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le rôle DE s'il n'existe pas
        $deRole = Role::firstOrCreate(['name' => 'DE']);

        // Créer l'utilisateur DE avec les identifiants spécifiés
        User::updateOrCreate(
            ['username' => 'DE'],
            [
                'username' => 'DE',
                'password' => '123456789',
                'phone' => 12345678,
                'role_id' => $deRole->id,
                'bat' => '0',
            ]
        );
    }
}
