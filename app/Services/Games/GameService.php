<?php

declare(strict_types=1);

namespace App\Services\Games;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameService
{
    public function __construct(
        protected GameSearchService $searchService
    ) {}

    /**
     * Cadastra ou atualiza um jogo e vincula à biblioteca do usuário com suas informações de progresso
     *
     * @param  array{
     *     title: string,
     *     slug?: string|null,
     *     cover_image?: string|null,
     *     background_image?: string|null,
     *     synopsis?: string|null,
     *     release_year?: int|string|null,
     *     developer?: string|null,
     *     publisher?: string|null,
     *     trailer_url?: string|null,
     *     is_franchise?: bool,
     *     franchise_name?: string|null,
     *     age_rating?: string|null,
     *     purchase_links?: array<string, string>|null,
     *     genre?: array<int, string>|null,
     *     platforms?: array<int, int>|null,
     *     libraries?: array<int, int>|null,
     *     existing_game_id?: int|null
     * }  $gameData
     * @param  array{
     *     status: string|GameStatus,
     *     hours_played?: float|int|string|null,
     *     rating?: float|int|string|null,
     *     review?: string|null
     * }  $userGameData
     */
    public function createOrUpdateGameForUser(
        User $user,
        array $gameData,
        array $userGameData,
        ?UploadedFile $coverFile = null,
        ?UploadedFile $backgroundFile = null
    ): UserGame {
        return DB::transaction(function () use ($user, $gameData, $userGameData, $coverFile, $backgroundFile) {
            // Se forneceu arquivo de imagem, realiza upload para storage local público
            if ($coverFile instanceof UploadedFile) {
                $coverPath = $coverFile->store('covers', 'public');
                $gameData['cover_image'] = Storage::url($coverPath);
            }

            if ($backgroundFile instanceof UploadedFile) {
                $bgPath = $backgroundFile->store('backgrounds', 'public');
                $gameData['background_image'] = Storage::url($bgPath);
            }

            $existingGameId = $gameData['existing_game_id'] ?? null;
            $game = null;

            if ($existingGameId) {
                $game = Game::find($existingGameId);
            }

            if (! $game) {
                // Gera slug único a partir do título
                $baseSlug = Str::slug($gameData['title']);
                $slug = $baseSlug;
                $counter = 1;

                while (Game::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }

                $game = Game::create([
                    'title' => trim($gameData['title']),
                    'slug' => $slug,
                    'cover_image' => ! empty($gameData['cover_image']) ? trim($gameData['cover_image']) : null,
                    'background_image' => ! empty($gameData['background_image']) ? trim($gameData['background_image']) : null,
                    'synopsis' => ! empty($gameData['synopsis']) ? trim($gameData['synopsis']) : null,
                    'release_year' => ! empty($gameData['release_year']) ? (int) $gameData['release_year'] : null,
                    'developer' => ! empty($gameData['developer']) ? trim($gameData['developer']) : null,
                    'publisher' => ! empty($gameData['publisher']) ? trim($gameData['publisher']) : null,
                    'trailer_url' => ! empty($gameData['trailer_url']) ? trim($gameData['trailer_url']) : null,
                    'is_franchise' => (bool) ($gameData['is_franchise'] ?? false),
                    'franchise_name' => ! empty($gameData['franchise_name']) ? trim($gameData['franchise_name']) : null,
                    'age_rating' => ! empty($gameData['age_rating']) ? trim($gameData['age_rating']) : null,
                    'purchase_links' => $gameData['purchase_links'] ?? null,
                    'genre' => ! empty($gameData['genre']) ? array_values(array_filter($gameData['genre'])) : null,
                ]);
            } else {
                // Atualiza atributos para o jogo existente
                $updates = [];
                if (isset($gameData['title']) && trim($gameData['title']) !== '') {
                    $updates['title'] = trim($gameData['title']);
                }
                if (array_key_exists('cover_image', $gameData)) {
                    $updates['cover_image'] = ! empty($gameData['cover_image']) ? trim($gameData['cover_image']) : null;
                }
                if (array_key_exists('background_image', $gameData)) {
                    $updates['background_image'] = ! empty($gameData['background_image']) ? trim($gameData['background_image']) : null;
                }
                if (array_key_exists('synopsis', $gameData)) {
                    $updates['synopsis'] = ! empty($gameData['synopsis']) ? trim($gameData['synopsis']) : null;
                }
                if (array_key_exists('release_year', $gameData)) {
                    $updates['release_year'] = ! empty($gameData['release_year']) ? (int) $gameData['release_year'] : null;
                }
                if (array_key_exists('developer', $gameData)) {
                    $updates['developer'] = ! empty($gameData['developer']) ? trim($gameData['developer']) : null;
                }
                if (array_key_exists('publisher', $gameData)) {
                    $updates['publisher'] = ! empty($gameData['publisher']) ? trim($gameData['publisher']) : null;
                }
                if (array_key_exists('trailer_url', $gameData)) {
                    $updates['trailer_url'] = ! empty($gameData['trailer_url']) ? trim($gameData['trailer_url']) : null;
                }
                if (array_key_exists('is_franchise', $gameData)) {
                    $updates['is_franchise'] = (bool) $gameData['is_franchise'];
                }
                if (array_key_exists('franchise_name', $gameData)) {
                    $updates['franchise_name'] = ! empty($gameData['franchise_name']) ? trim($gameData['franchise_name']) : null;
                }
                if (array_key_exists('age_rating', $gameData)) {
                    $updates['age_rating'] = ! empty($gameData['age_rating']) ? trim((string) $gameData['age_rating']) : null;
                }
                if (array_key_exists('genre', $gameData)) {
                    $updates['genre'] = ! empty($gameData['genre']) ? array_values(array_filter($gameData['genre'])) : null;
                }
                if (array_key_exists('purchase_links', $gameData)) {
                    $updates['purchase_links'] = ! empty($gameData['purchase_links']) ? $gameData['purchase_links'] : null;
                }

                if (! empty($updates)) {
                    $game->update($updates);
                }
            }

            // Sincroniza plataformas e bibliotecas selecionadas
            if (isset($gameData['platforms'])) {
                $game->platforms()->sync(array_filter((array) $gameData['platforms']));
            }

            if (isset($gameData['libraries'])) {
                $game->libraries()->sync(array_filter((array) $gameData['libraries']));
            }

            // Trata informações de progresso do usuário (UserGame)
            $status = $userGameData['status'] instanceof GameStatus
                ? $userGameData['status']
                : (GameStatus::tryFrom((string) $userGameData['status']) ?? GameStatus::Backlog);

            $hoursPlayed = max(0.0, (float) ($userGameData['hours_played'] ?? 0.0));

            $rating = null;
            if (isset($userGameData['rating']) && $userGameData['rating'] !== '' && $userGameData['rating'] !== null) {
                $rating = min(5.0, max(0.0, (float) $userGameData['rating']));
            }

            $review = ! empty($userGameData['review']) ? trim((string) $userGameData['review']) : null;
            $finishedAt = $status === GameStatus::Finished ? Carbon::now() : null;

            $userGame = UserGame::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                ],
                [
                    'status' => $status,
                    'hours_played' => $hoursPlayed,
                    'rating' => $rating,
                    'review' => $review,
                    'finished_at' => $finishedAt,
                ]
            );

            // Sincroniza imediatamente com o Elasticsearch
            $this->searchService->indexGame($game->fresh(['platforms', 'libraries']) ?? $game);

            return $userGame;
        });
    }

    /**
     * Busca jogos existentes no banco de dados para sugestão e pré-preenchimento
     *
     * @return Collection<int, Game>
     */
    public function searchCatalog(string $query, int $limit = 8): Collection
    {
        $term = trim($query);
        if ($term === '') {
            return new Collection;
        }

        $termLower = mb_strtolower($term);

        return Game::with(['platforms', 'libraries'])
            ->where(function ($q) use ($termLower) {
                $pattern = "%{$termLower}%";
                $q->whereRaw('LOWER(title) LIKE ?', [$pattern])
                    ->orWhereRaw('LOWER(developer) LIKE ?', [$pattern])
                    ->orWhereRaw('LOWER(franchise_name) LIKE ?', [$pattern]);
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Retorna todas as plataformas disponíveis (padrão e personalizadas do usuário)
     *
     * @return Collection<int, Platform>
     */
    public function getAvailablePlatformsForUser(int $userId): Collection
    {
        return Platform::where(function ($q) use ($userId) {
            $q->where('is_custom', false)
                ->orWhere('user_id', $userId);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Retorna todas as bibliotecas disponíveis (padrão e personalizadas do usuário)
     *
     * @return Collection<int, GameLibrary>
     */
    public function getAvailableLibrariesForUser(int $userId): Collection
    {
        return GameLibrary::where(function ($q) use ($userId) {
            $q->where('is_custom', false)
                ->orWhere('user_id', $userId);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtém os gêneros cadastrados no banco de dados para sugestões
     *
     * @return array<int, string>
     */
    public function getAvailableGenres(): array
    {
        $defaultGenres = [
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

        $games = Game::whereNotNull('genre')->get(['genre']);
        $existing = [];

        foreach ($games as $game) {
            if (is_array($game->genre)) {
                foreach ($game->genre as $g) {
                    $trimmed = trim((string) $g);
                    if ($trimmed !== '') {
                        $existing[] = $trimmed;
                    }
                }
            }
        }

        $all = array_values(array_unique(array_merge($defaultGenres, $existing)));
        sort($all);

        return $all;
    }
}
