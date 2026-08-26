<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = Platform::all()->keyBy('slug');
        $libraries = GameLibrary::all()->keyBy('slug');

        $games = [
            [
                'title' => 'The Legend of Zelda: Breath of the Wild',
                'slug' => 'the-legend-of-zelda-breath-of-the-wild',
                'cover_image' => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Esqueça tudo o que você sabe sobre os jogos The Legend of Zelda. Entre em um mundo de descobertas, exploração e aventura em The Legend of Zelda: Breath of the Wild.',
                'release_year' => 2017,
                'developer' => 'Nintendo EPD',
                'publisher' => 'Nintendo',
                'trailer_url' => 'https://www.youtube.com/watch?v=zw47_q9wbBE',
                'is_franchise' => true,
                'franchise_name' => 'The Legend of Zelda',
                'age_rating' => '10+',
                'genre' => ['Ação', 'Aventura', 'RPG', 'Mundo Aberto'],
                'platforms' => ['nintendo-switch'],
                'libraries' => [],
            ],
            [
                'title' => 'Cyberpunk 2077',
                'slug' => 'cyberpunk-2077',
                'cover_image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Cyberpunk 2077 é um RPG de ação e aventura em mundo aberto ambientado na megalópole de Night City, onde você joga como um mercenário cyberpunk envolvido em uma luta de vida ou morte.',
                'release_year' => 2020,
                'developer' => 'CD PROJEKT RED',
                'publisher' => 'CD PROJEKT RED',
                'trailer_url' => 'https://www.youtube.com/watch?v=qIcTM8WXFjk',
                'is_franchise' => true,
                'franchise_name' => 'Cyberpunk',
                'age_rating' => '18+',
                'genre' => ['RPG', 'Ação', 'Sci-Fi', 'Mundo Aberto'],
                'platforms' => ['pc', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam', 'gog', 'epic-games'],
            ],
            [
                'title' => 'Elden Ring',
                'slug' => 'elden-ring',
                'cover_image' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'O novo RPG de ação e fantasia da FromSoftware e de Hidetaka Miyazaki, criado em colaboração com George R. R. Martin. Levante-se, Maculado, e seja guiado pela graça para empunhar o poder do Anel Prístino.',
                'release_year' => 2022,
                'developer' => 'FromSoftware Inc.',
                'publisher' => 'Bandai Namco Entertainment',
                'trailer_url' => 'https://www.youtube.com/watch?v=E3Huy2cdih0',
                'is_franchise' => true,
                'franchise_name' => 'Soulsborne',
                'age_rating' => '16+',
                'genre' => ['RPG de Ação', 'Soulslike', 'Mundo Aberto', 'Fantasia Sombria'],
                'platforms' => ['pc', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam'],
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'slug' => 'the-witcher-3-wild-hunt',
                'cover_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Você é Geralt de Rívia, caçador de monstros. Em uma terra devastada pela guerra e infestada por feras, seu contrato é encontrar Ciri, a Criança da Profecia.',
                'release_year' => 2015,
                'developer' => 'CD PROJEKT RED',
                'publisher' => 'CD PROJEKT RED',
                'trailer_url' => 'https://www.youtube.com/watch?v=c0i88t0Kacs',
                'is_franchise' => true,
                'franchise_name' => 'The Witcher',
                'age_rating' => '18+',
                'genre' => ['RPG', 'Ação', 'Mundo Aberto', 'Fantasia'],
                'platforms' => ['pc', 'playstation-sony', 'xbox-microsoft', 'nintendo-switch'],
                'libraries' => ['steam', 'gog', 'epic-games'],
            ],
            [
                'title' => "Baldur's Gate 3",
                'slug' => 'baldurs-gate-3',
                'cover_image' => 'https://images.unsplash.com/photo-1563089145-599997674d42?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1563089145-599997674d42?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Reúna seu grupo e retorne aos Reinos Esquecidos em uma história de companheirismo e traição, sacrifício e sobrevivência, e a atração do poder absoluto.',
                'release_year' => 2023,
                'developer' => 'Larian Studios',
                'publisher' => 'Larian Studios',
                'trailer_url' => 'https://www.youtube.com/watch?v=1T22wNlUiU8',
                'is_franchise' => true,
                'franchise_name' => "Baldur's Gate",
                'age_rating' => '18+',
                'genre' => ['RPG', 'Estratégia', 'Turno', 'Fantasia'],
                'platforms' => ['pc', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam', 'gog'],
            ],
            [
                'title' => 'Red Dead Redemption 2',
                'slug' => 'red-dead-redemption-2',
                'cover_image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Estados Unidos, 1899. O fim da era do velho oeste começou. Depois que um assalto dá errado na cidade de Blackwater, Arthur Morgan e a gangue Van der Linde são forçados a fugir.',
                'release_year' => 2018,
                'developer' => 'Rockstar Studios',
                'publisher' => 'Rockstar Games',
                'trailer_url' => 'https://www.youtube.com/watch?v=eaW0tYpxyp0',
                'is_franchise' => true,
                'franchise_name' => 'Red Dead',
                'age_rating' => '18+',
                'genre' => ['Ação', 'Aventura', 'Mundo Aberto', 'Faroeste'],
                'platforms' => ['pc', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam', 'rockstar-launcher', 'epic-games'],
            ],
            [
                'title' => 'God of War Ragnarök',
                'slug' => 'god-of-war-ragnarok',
                'cover_image' => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Kratos e Atreus devem viajar pelos Nove Reinos em busca de respostas enquanto as forças de Asgard se preparam para uma guerra profetizada que acabará com o mundo.',
                'release_year' => 2022,
                'developer' => 'Santa Monica Studio',
                'publisher' => 'Sony Interactive Entertainment',
                'trailer_url' => 'https://www.youtube.com/watch?v=EE-4GvjKcfs',
                'is_franchise' => true,
                'franchise_name' => 'God of War',
                'age_rating' => '18+',
                'genre' => ['Ação', 'Aventura', 'Hack and Slash', 'Mitologia'],
                'platforms' => ['playstation-sony', 'pc'],
                'libraries' => ['steam'],
            ],
            [
                'title' => 'Super Mario Odyssey',
                'slug' => 'super-mario-odyssey',
                'cover_image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Embarque em uma aventura em 3D estilo sandbox pelo mundo com Mario e seu novo aliado Cappy para resgatar a Princesa Peach dos planos de casamento de Bowser.',
                'release_year' => 2017,
                'developer' => 'Nintendo EPD',
                'publisher' => 'Nintendo',
                'trailer_url' => 'https://www.youtube.com/watch?v=wGQHQc_3ycE',
                'is_franchise' => true,
                'franchise_name' => 'Super Mario',
                'age_rating' => 'Livre',
                'genre' => ['Plataforma', 'Aventura', '3D'],
                'platforms' => ['nintendo-switch'],
                'libraries' => [],
            ],
            [
                'title' => 'Hollow Knight',
                'slug' => 'hollow-knight',
                'cover_image' => 'https://images.unsplash.com/photo-1551103782-8ab07afd45c1?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1551103782-8ab07afd45c1?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Forje seu próprio caminho em Hollow Knight! Uma aventura épica através de um vasto reino arruinado de insetos e heróis sob a decadente cidade de Dirtmouth.',
                'release_year' => 2017,
                'developer' => 'Team Cherry',
                'publisher' => 'Team Cherry',
                'trailer_url' => 'https://www.youtube.com/watch?v=UAO2urG23S4',
                'is_franchise' => true,
                'franchise_name' => 'Hollow Knight',
                'age_rating' => '10+',
                'genre' => ['Metroidvania', 'Indie', 'Ação', 'Plataforma'],
                'platforms' => ['pc', 'nintendo-switch', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam', 'gog'],
            ],
            [
                'title' => 'Hades',
                'slug' => 'hades',
                'cover_image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Desafie o deus dos mortos enquanto você batalha para escapar do Submundo neste jogo de ação rogue-like no melhor estilo hack-and-slash dos criadores de Bastion e Transistor.',
                'release_year' => 2020,
                'developer' => 'Supergiant Games',
                'publisher' => 'Supergiant Games',
                'trailer_url' => 'https://www.youtube.com/watch?v=91t0HA5PyeI',
                'is_franchise' => true,
                'franchise_name' => 'Hades',
                'age_rating' => '14+',
                'genre' => ['Roguelike', 'Ação', 'Indie', 'Mitologia'],
                'platforms' => ['pc', 'nintendo-switch', 'playstation-sony', 'xbox-microsoft'],
                'libraries' => ['steam', 'epic-games'],
            ],
            [
                'title' => 'Forza Horizon 5',
                'slug' => 'forza-horizon-5',
                'cover_image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Sua maior aventura Horizon te espera! Explore os cenários vibrantes e em constante evolução do México com uma ação de direção divertida e ilimitada.',
                'release_year' => 2021,
                'developer' => 'Playground Games',
                'publisher' => 'Xbox Game Studios',
                'trailer_url' => 'https://www.youtube.com/watch?v=FYH9n3Ov126',
                'is_franchise' => true,
                'franchise_name' => 'Forza',
                'age_rating' => 'Livre',
                'genre' => ['Corrida', 'Mundo Aberto', 'Simulação', 'Esportes'],
                'platforms' => ['pc', 'xbox-microsoft'],
                'libraries' => ['steam', 'xbox-pc'],
            ],
            [
                'title' => 'Starfield',
                'slug' => 'starfield',
                'cover_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&h=900&q=80',
                'background_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1920&q=80',
                'synopsis' => 'Starfield é o primeiro universo novo em mais de 25 anos da Bethesda Game Studios. Crie qualquer personagem que desejar e explore com liberdade sem igual.',
                'release_year' => 2023,
                'developer' => 'Bethesda Game Studios',
                'publisher' => 'Bethesda Softworks',
                'trailer_url' => 'https://www.youtube.com/watch?v=kfYEiTdsyas',
                'is_franchise' => false,
                'franchise_name' => null,
                'age_rating' => '16+',
                'genre' => ['RPG', 'Sci-Fi', 'Espaço', 'Mundo Aberto'],
                'platforms' => ['pc', 'xbox-microsoft'],
                'libraries' => ['steam', 'xbox-pc'],
            ],
        ];

        foreach ($games as $gameData) {
            $platformSlugs = $gameData['platforms'];
            $librarySlugs = $gameData['libraries'];
            unset($gameData['platforms'], $gameData['libraries']);

            $game = Game::updateOrCreate(
                ['slug' => $gameData['slug']],
                $gameData
            );

            $platformIds = [];
            foreach ($platformSlugs as $slug) {
                if (isset($platforms[$slug])) {
                    $platformIds[] = $platforms[$slug]->id;
                }
            }
            $game->platforms()->sync($platformIds);

            $libraryIds = [];
            foreach ($librarySlugs as $slug) {
                if (isset($libraries[$slug])) {
                    $libraryIds[] = $libraries[$slug]->id;
                }
            }
            $game->libraries()->sync($libraryIds);
        }
    }
}
