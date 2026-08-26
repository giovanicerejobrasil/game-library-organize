<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'giovani@example.com')->first();

        if (! $user) {
            return;
        }

        $games = Game::all()->keyBy('slug');

        $userGamesData = [
            'the-legend-of-zelda-breath-of-the-wild' => [
                'status' => GameStatus::Finished,
                'hours_played' => 145.5,
                'rating' => 5.0,
                'review' => 'Uma obra de arte absoluta. A sensação de descoberta e liberdade é sem precedentes no mundo dos games.',
                'finished_at' => Carbon::now()->subMonths(6),
            ],
            'cyberpunk-2077' => [
                'status' => GameStatus::Playing,
                'hours_played' => 62.0,
                'rating' => 4.5,
                'review' => 'Após todas as atualizações e a expansão Phantom Liberty, Night City se tornou um dos mundos mais imersivos já criados.',
                'finished_at' => null,
            ],
            'elden-ring' => [
                'status' => GameStatus::Finished,
                'hours_played' => 110.0,
                'rating' => 5.0,
                'review' => 'Desafiador, recompensador e monumental. O design de mundo e chefes é inacreditável.',
                'finished_at' => Carbon::now()->subMonths(2),
            ],
            'the-witcher-3-wild-hunt' => [
                'status' => GameStatus::Finished,
                'hours_played' => 180.0,
                'rating' => 5.0,
                'review' => 'Narrativa excepcional e missões secundárias que dão aula de escrita e desenvolvimento de personagens.',
                'finished_at' => Carbon::now()->subYears(1),
            ],
            'baldurs-gate-3' => [
                'status' => GameStatus::Playing,
                'hours_played' => 84.5,
                'rating' => 4.8,
                'review' => 'A profundidade de escolhas e interpretação de papéis deste RPG é incomparável.',
                'finished_at' => null,
            ],
            'red-dead-redemption-2' => [
                'status' => GameStatus::Finished,
                'hours_played' => 95.0,
                'rating' => 4.9,
                'review' => 'A jornada de Arthur Morgan é emocionante e o nível de detalhes do mundo é surreal.',
                'finished_at' => Carbon::now()->subMonths(8),
            ],
            'god-of-war-ragnarok' => [
                'status' => GameStatus::Finished,
                'hours_played' => 48.0,
                'rating' => 4.7,
                'review' => 'Combate visceral e uma conclusão digna e emocionante para a saga nórdica de Kratos.',
                'finished_at' => Carbon::now()->subMonths(4),
            ],
            'super-mario-odyssey' => [
                'status' => GameStatus::Finished,
                'hours_played' => 35.0,
                'rating' => 4.8,
                'review' => 'Pura diversão e criatividade em cada reino. Controle impecável.',
                'finished_at' => Carbon::now()->subYears(2),
            ],
            'hollow-knight' => [
                'status' => GameStatus::Finished,
                'hours_played' => 52.0,
                'rating' => 5.0,
                'review' => 'O ápice dos metroidvanias. Atmosfera, trilha sonora e combate impecáveis.',
                'finished_at' => Carbon::now()->subMonths(10),
            ],
            'hades' => [
                'status' => GameStatus::Finished,
                'hours_played' => 70.0,
                'rating' => 4.9,
                'review' => 'Roguelike viciante com ritmo de combate perfeito e história muito bem amarrada.',
                'finished_at' => Carbon::now()->subMonths(5),
            ],
            'forza-horizon-5' => [
                'status' => GameStatus::Backlog,
                'hours_played' => 8.0,
                'rating' => 4.0,
                'review' => 'Visual espetacular, ótimo para relaxar dirigindo pelas paisagens mexicanas.',
                'finished_at' => null,
            ],
            'starfield' => [
                'status' => GameStatus::Dropped,
                'hours_played' => 18.0,
                'rating' => 3.0,
                'review' => 'Boa premissa e construção de naves, mas a exploração espacial com muitos loadings quebrou o ritmo.',
                'finished_at' => null,
            ],
        ];

        foreach ($userGamesData as $slug => $data) {
            if (isset($games[$slug])) {
                UserGame::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'game_id' => $games[$slug]->id,
                    ],
                    [
                        'status' => $data['status'],
                        'hours_played' => $data['hours_played'],
                        'rating' => $data['rating'],
                        'review' => $data['review'],
                        'finished_at' => $data['finished_at'],
                    ]
                );
            }
        }
    }
}
