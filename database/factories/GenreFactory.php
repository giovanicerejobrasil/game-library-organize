<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Genre>
 */
class GenreFactory extends Factory
{
    protected $model = Genre::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'is_custom' => false,
            'user_id' => null,
        ];
    }

    /**
     * Indicate that the genre is custom.
     */
    public function custom(?int $userId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'is_custom' => true,
            'user_id' => $userId,
        ]);
    }
}
