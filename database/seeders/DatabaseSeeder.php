<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('adminadmin'),
                'is_admin' => true,
                'strength' => 'S',
                'constitution' => 'S',
                'intelligence' => 'S',
                'charisma' => 'S',
                'daily_quest_count' => 3,
                'quest_attributes' => ['strength', 'constitution', 'intelligence', 'charisma'],
                'last_daily_refresh' => now(),
                'assessment_completed' => true,
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'daily_quest_count' => 3,
            'quest_attributes' => ['strength', 'constitution', 'intelligence', 'charisma'],
            'last_daily_refresh' => now(),
            'assessment_completed' => true,
        ]);
    }
}
