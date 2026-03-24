<?php

namespace Database\Factories;

use App\Models\Quest;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestFactory extends Factory
{
    protected $model = Quest::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'stat' => $this->faker->randomElement(['strength', 'constitution', 'intelligence', 'charisma']),
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
        ];
    }
}
