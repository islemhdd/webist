<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = [1, 2, 3];
        $grade = fake()->randomElement($grades);
        $companyLetters = ['A', 'B', 'C'];

        return [
            'name' => $grade . fake()->randomElement($companyLetters),
            'grade' => $grade,
            'bat' => fake()->numberBetween(1, 5),
            'companie' => fake()->randomElement($companyLetters),
            'num' => fake()->numberBetween(1, 10),
            'officer_id' => User::where('role', 'officer')->inRandomOrder()->first()?->id,
        ];
    }
}
