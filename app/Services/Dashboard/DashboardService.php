<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function __construct(
        protected GameSearchService $searchService
    ) {}

    /**
     * Calcula as métricas e estatísticas consolidadas da biblioteca do usuário
     *
     * @return array{
     *     total_games: int,
     *     finished_count: int,
     *     finished_percentage: float,
     *     playing_count: int,
     *     backlog_count: int,
     *     dropped_count: int,
     *     total_hours_played: float,
     *     average_rating: float
     * }
     */
    public function getStatistics(int $userId): array
    {
        $userGames = UserGame::where('user_id', $userId)->get();

        $totalGames = $userGames->count();
        $finishedCount = $userGames->where('status', GameStatus::Finished)->count();
        $playingCount = $userGames->where('status', GameStatus::Playing)->count();
        $backlogCount = $userGames->where('status', GameStatus::Backlog)->count();
        $droppedCount = $userGames->where('status', GameStatus::Dropped)->count();

        $totalHours = (float) $userGames->sum('hours_played');
        $ratedGames = $userGames->whereNotNull('rating')->where('rating', '>', 0);
        $avgRating = $ratedGames->isNotEmpty() ? (float) $ratedGames->avg('rating') : 0.0;

        $finishedPercentage = $totalGames > 0
            ? round(($finishedCount / $totalGames) * 100, 1)
            : 0.0;

        return [
            'total_games' => $totalGames,
            'finished_count' => $finishedCount,
            'finished_percentage' => $finishedPercentage,
            'playing_count' => $playingCount,
            'backlog_count' => $backlogCount,
            'dropped_count' => $droppedCount,
            'total_hours_played' => round($totalHours, 1),
            'average_rating' => round($avgRating, 1),
        ];
    }

    /**
     * Consulta jogos com suporte a filtros combinados, ordenação, busca (Elasticsearch/SQL) e paginação
     *
     * @param  array{
     *     search?: string|null,
     *     platform?: string|null,
     *     library?: string|null,
     *     status?: string|null,
     *     genre?: string|null,
     *     year?: int|string|null,
     *     sort_by?: string|null,
     *     scope?: string|null
     * }  $filters
     */
    public function getGames(int $userId, array $filters = [], int $perPage = 18): LengthAwarePaginator
    {
        $query = Game::query()
            ->with([
                'platforms',
                'libraries',
                'userGames' => fn ($q) => $q->where('user_id', $userId),
            ]);

        $scope = $filters['scope'] ?? 'my_library';

        if ($scope === 'my_library') {
            $query->whereHas('userGames', function (Builder $q) use ($userId, $filters) {
                $q->where('user_id', $userId);

                if (! empty($filters['status'])) {
                    $q->where('status', $filters['status']);
                }
            });
        } elseif (! empty($filters['status'])) {
            $query->whereHas('userGames', function (Builder $q) use ($userId, $filters) {
                $q->where('user_id', $userId)
                    ->where('status', $filters['status']);
            });
        }

        // Filtro por Plataforma
        if (! empty($filters['platform'])) {
            $query->whereHas('platforms', function (Builder $q) use ($filters) {
                $q->where('slug', $filters['platform']);
            });
        }

        // Filtro por Biblioteca Digital
        if (! empty($filters['library'])) {
            $query->whereHas('libraries', function (Builder $q) use ($filters) {
                $q->where('slug', $filters['library']);
            });
        }

        // Filtro por Ano de Lançamento
        if (! empty($filters['year'])) {
            $query->where('release_year', (int) $filters['year']);
        }

        // Filtro por Gênero
        if (! empty($filters['genre'])) {
            $genre = trim((string) $filters['genre']);
            $genreLower = mb_strtolower($genre);
            $query->where(function (Builder $q) use ($genre, $genreLower) {
                $q->whereJsonContains('genre', $genre)
                    ->orWhereRaw('LOWER(genre) LIKE ?', ["%{$genreLower}%"]);
            });
        }

        // Busca Textual com Elasticsearch e Fallback SQL (Case-Insensitive)
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $elasticIds = [];

            if ($this->searchService->isAvailable()) {
                $searchResult = $this->searchService->search(['query' => $search]);
                $elasticIds = $searchResult['ids'] ?? [];
            }

            if (! empty($elasticIds)) {
                $query->whereIn('games.id', $elasticIds);
            } else {
                $searchLower = mb_strtolower($search);
                $term = "%{$searchLower}%";
                $query->where(function (Builder $q) use ($term) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(developer) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(publisher) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(franchise_name) LIKE ?', [$term]);
                });
            }
        }

        // Ordenação
        $sortBy = $filters['sort_by'] ?? 'recent';

        switch ($sortBy) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;

            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;

            case 'year_desc':
                $query->orderBy('release_year', 'desc');
                break;

            case 'year_asc':
                $query->orderBy('release_year', 'asc');
                break;

            case 'rating_desc':
                $query->leftJoin('user_games as ug_rating', function ($join) use ($userId) {
                    $join->on('games.id', '=', 'ug_rating.game_id')
                        ->where('ug_rating.user_id', '=', $userId);
                })
                    ->select('games.*')
                    ->orderByRaw('COALESCE(ug_rating.rating, 0) DESC')
                    ->orderBy('games.title', 'asc');
                break;

            case 'hours_desc':
                $query->leftJoin('user_games as ug_hours', function ($join) use ($userId) {
                    $join->on('games.id', '=', 'ug_hours.game_id')
                        ->where('ug_hours.user_id', '=', $userId);
                })
                    ->select('games.*')
                    ->orderByRaw('COALESCE(ug_hours.hours_played, 0) DESC')
                    ->orderBy('games.title', 'asc');
                break;

            case 'recent':
            default:
                $query->leftJoin('user_games as ug_recent', function ($join) use ($userId) {
                    $join->on('games.id', '=', 'ug_recent.game_id')
                        ->where('ug_recent.user_id', '=', $userId);
                })
                    ->select('games.*')
                    ->orderByRaw('COALESCE(ug_recent.updated_at, games.created_at) DESC');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Atualiza ou cria o registro de progresso do usuário para um jogo
     *
     * @param  array{
     *     status: string|GameStatus,
     *     hours_played: float|int|string,
     *     rating?: float|int|string|null,
     *     review?: string|null
     * }  $data
     */
    public function updateGameProgress(int $userId, int $gameId, array $data): UserGame
    {
        $status = $data['status'] instanceof GameStatus
            ? $data['status']
            : (GameStatus::tryFrom((string) $data['status']) ?? GameStatus::Backlog);

        $hoursPlayed = max(0.0, (float) ($data['hours_played'] ?? 0.0));
        $rating = isset($data['rating']) && $data['rating'] !== '' && $data['rating'] !== null
            ? min(5.0, max(0.0, (float) $data['rating']))
            : null;

        $review = ! empty($data['review']) ? trim((string) $data['review']) : null;

        $finishedAt = $status === GameStatus::Finished ? Carbon::now() : null;

        return UserGame::updateOrCreate(
            [
                'user_id' => $userId,
                'game_id' => $gameId,
            ],
            [
                'status' => $status,
                'hours_played' => $hoursPlayed,
                'rating' => $rating,
                'review' => $review,
                'finished_at' => $finishedAt,
            ]
        );
    }

    /**
     * Obtém as opções de plataformas disponíveis
     */
    public function getAvailablePlatforms(): array
    {
        return Platform::orderBy('name')->get(['id', 'name', 'slug', 'icon'])->toArray();
    }

    /**
     * Obtém as opções de bibliotecas disponíveis
     */
    public function getAvailableLibraries(): array
    {
        return GameLibrary::orderBy('name')->get(['id', 'name', 'slug', 'icon'])->toArray();
    }

    /**
     * Obtém todos os gêneros únicos cadastrados
     *
     * @return array<int, string>
     */
    public function getAvailableGenres(): array
    {
        $allGenres = [];
        $games = Game::whereNotNull('genre')->get(['genre']);

        foreach ($games as $game) {
            if (is_array($game->genre)) {
                foreach ($game->genre as $g) {
                    $allGenres[] = trim((string) $g);
                }
            }
        }

        $unique = array_values(array_unique(array_filter($allGenres)));
        sort($unique);

        return $unique;
    }
}
