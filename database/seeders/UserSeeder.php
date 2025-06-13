<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user


        // Create users with specific roles
        $roles = ['Chef de compagnie', 'Chef de brigade', 'Chef de batallaint', 'Chef division', 'Medecin'];
        foreach ($roles as $index => $role) {
            User::create([
                'username' => $role . ' User',

                'password' => Hash::make('11111111'),
                'phone' => '20000' . $index,
                'role_id' => Role::where('name', $role)->first()->id
            ]);
        }
    }
}
