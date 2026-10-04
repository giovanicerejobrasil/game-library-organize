<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Ação',
            'Aventura',
            'RPG',
            'Mundo Aberto',
            'Estratégia',
            'FPS / Tiro',
            'Plataforma',
            'Metroidvania',
            'Roguelike',
            'Terror / Sobrevivência',
            'Simulação',
            'Corrida',
            'Luta',
            'Puzzle',
            'Indie',
            'Sci-Fi',
            'Fantasia',
        ];

        foreach ($genres as $name) {
            $slug = Str::slug($name);
            Genre::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'is_custom' => false,
                    'user_id' => null,
                ]
            );
        }
    }
}
