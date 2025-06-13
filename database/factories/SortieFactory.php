<?php

namespace Database\Factories;

use App\Models\Sortie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sortie>
 */
class SortieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sortie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+3 months')->setTime(16, 0);
        $endDate = (clone $startDate)->modify('+2 days')->setTime(18, 0);

        return [
            'name' => 'Weekend ' . fake()->date('Y-m-d'),
            'date_start' => $startDate,
            'date_end' => $endDate,
            'locked' => fake()->boolean(20), // 20% chance of being locked
        ];
    }
}
