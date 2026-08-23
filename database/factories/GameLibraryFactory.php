<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\GameLibrary;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<GameLibrary>
 */
class GameLibraryFactory extends Factory
{
    protected $model = GameLibrary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => null,
            'is_custom' => false,
            'user_id' => null,
        ];
    }
}
