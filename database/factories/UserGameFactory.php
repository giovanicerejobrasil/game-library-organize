<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserGame>
 */
class UserGameFactory extends Factory
{
    protected $model = UserGame::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => Game::factory(),
            'status' => fake()->randomElement(GameStatus::cases()),
            'hours_played' => fake()->randomFloat(2, 0, 150),
            'rating' => fake()->randomFloat(1, 1, 5),
            'review' => fake()->sentence(),
            'finished_at' => null,
        ];
    }
}
