<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [
            'admin' => 'Administrator with full privileges',
            'officer' => 'School officer with management privileges',
            'student' => 'Student with limited privileges',
            'Chef de compagnie' => 'Company leader',
            'Chef de brigade' => 'Brigade leader',
            'Chef de batallaint' => 'Battalion leader',
            'Chef division' => 'Division leader',
            'Medecin' => 'Medical doctor',
            'Directeur général' => 'General director',
        ];

        $role = fake()->randomElement(array_keys($roles));

        return [
            'name' => $role,
            'description' => $roles[$role],
        ];
    }
}
