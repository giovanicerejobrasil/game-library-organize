<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(rand(2, 4));

        return [
            'title' => ucwords($title),
            'slug' => Str::slug($title),
            'cover_image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80',
            'background_image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1920&q=80',
            'synopsis' => fake()->paragraph(),
            'release_year' => fake()->numberBetween(1990, 2026),
            'developer' => fake()->company(),
            'publisher' => fake()->company(),
            'trailer_url' => 'https://www.youtube.com/watch?v=NXTlh31ZWZ4',
            'is_franchise' => false,
            'franchise_name' => null,
            'age_rating' => '18+',
            'purchase_links' => [
                'steam' => 'https://store.steampowered.com/app/3240220/Grand_Theft_Auto_V_Enhanced/',
                'epic' => 'https://store.epicgames.com/p/grand-theft-auto-v',
            ],
            'genre' => ['Action', 'RPG'],
        ];
    }
}
