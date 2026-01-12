<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data from webist.sql - users table
     * Note: All users have the same bcrypt password hash
     */
    public function run(): void
    {
        // Note: Don't use Hash::make() here - the User model has a setPasswordAttribute mutator
        // that automatically hashes the password. Passing plain text avoids double-hashing.
        $password = 'Dev#Secure2024!';

        $users = [
            [
                'id' => 1,
                'username' => 'bou',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '3',
                'role_id' => 1,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 3,
                'username' => 'da9',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '3',
                'role_id' => 2,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 4,
                'username' => 'bouma3',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '0',
                'role_id' => 3,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 5,
                'username' => 'MED',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '0',
                'role_id' => 5,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 6,
                'username' => 'lab',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '0',
                'role_id' => 4,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 8,
                'username' => 'ham',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '2',
                'role_id' => 1,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
            [
                'id' => 9,
                'username' => 'DG',
                'password' => $password,
                'phone' => '770004546',
                'bat' => '0',
                'role_id' => 6,
                'created_at' => '2025-05-21 17:31:30',
                'updated_at' => '2025-05-21 17:31:30'
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['id' => $user['id']], $user);
        }
    }
}
