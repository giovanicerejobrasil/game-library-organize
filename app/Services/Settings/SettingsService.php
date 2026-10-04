<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\Enums\GameStatus;
use App\Enums\ThemeMode;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use App\Services\Support\ColorConverter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class SettingsService
{
    public const DEFAULT_BRAND_PRIMARY = '#182075';

    public const DEFAULT_BRAND_SECONDARY = '#751919';

    public function __construct(
        protected GameSearchService $searchService
    ) {}

    /**
     * Atualiza as informações básicas do perfil do usuário
     *
     * @param  array{name: string, email: string}  $data
     */
    public function updateProfile(User $user, array $data): void
    {
        $user->update([
            'name' => trim($data['name']),
            'email' => mb_strtolower(trim($data['email'])),
        ]);
    }

    /**
     * Atualiza a senha de acesso do usuário após conferência da senha atual
     *
     * @throws ValidationException
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'A senha atual informada está incorreta.',
            ]);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Atualiza as preferências visuais de tema e cores personalizadas
     */
    public function updateThemeSettings(
        User $user,
        ThemeMode|string $theme,
        string $brandPrimary,
        string $brandSecondary
    ): void {
        $themeValue = $theme instanceof ThemeMode ? $theme : (ThemeMode::tryFrom($theme) ?? ThemeMode::Dark);

        $user->update([
            'theme' => $themeValue,
            'brand_primary' => $brandPrimary,
            'brand_secondary' => $brandSecondary,
        ]);
    }

    /**
     * Restaura as preferências visuais padrão do sistema
     */
    public function resetThemeSettings(User $user): void
    {
        $user->update([
            'theme' => ThemeMode::Dark,
            'brand_primary' => self::DEFAULT_BRAND_PRIMARY,
            'brand_secondary' => self::DEFAULT_BRAND_SECONDARY,
        ]);
    }

    /**
     * Coleta informações e diagnósticos reais do sistema e infraestrutura (para administradores)
     * ou métricas restritas do catálogo do usuário comum.
     *
     * @return array{
     *     database: ?array{
     *         connected: bool,
     *         driver: string,
     *         database: string,
     *         host: string,
     *         port: string|int,
     *         version: string,
     *         latency_ms: float
     *     },
     *     elasticsearch: ?array{
     *         available: bool,
     *         host: string,
     *         index: string
     *     },
     *     stack: ?array{
     *         php_version: string,
     *         laravel_version: string,
     *         environment: string,
     *         debug: bool,
     *         timezone: string,
     *         session_driver: string,
     *         cache_driver: string
     *     },
     *     metrics: array{
     *         total_catalog_games: int,
     *         user_library_games: int,
     *         total_platforms: int,
     *         total_libraries: int,
     *         total_genres: int,
     *         total_users?: int,
     *         total_user_games?: int,
     *         finished_games?: int,
     *         playing_games?: int,
     *         backlog_games?: int,
     *         dropped_games?: int
     *     }
     * }
     */
    public function getSystemDiagnostics(User $user): array
    {
        if ($user->isAdmin()) {
            $dbDriver = (string) Config::get('database.default', 'pgsql');
            $dbName = (string) Config::get("database.connections.{$dbDriver}.database", 'game_library_organize');
            $dbHost = (string) Config::get("database.connections.{$dbDriver}.host", '127.0.0.1');
            $dbPort = Config::get("database.connections.{$dbDriver}.port", 5432);

            $dbConnected = false;
            $dbVersion = 'Desconhecido';
            $dbLatency = 0.0;

            try {
                $startTime = microtime(true);
                DB::connection()->getPdo();
                $dbLatency = round((microtime(true) - $startTime) * 1000, 2);
                $dbConnected = true;

                if ($dbDriver === 'sqlite') {
                    $versionRow = DB::selectOne('SELECT sqlite_version() as version');
                    if ($versionRow && isset($versionRow->version)) {
                        $dbVersion = 'SQLite '.(string) $versionRow->version;
                    }
                } elseif ($dbDriver === 'pgsql') {
                    $versionRow = DB::selectOne('SELECT version()');
                    if ($versionRow && isset($versionRow->version)) {
                        $dbVersion = Str::before((string) $versionRow->version, ',');
                    }
                } else {
                    $versionRow = DB::selectOne('SELECT version() as version');
                    if ($versionRow && isset($versionRow->version)) {
                        $dbVersion = (string) $versionRow->version;
                    }
                }
            } catch (Throwable) {
                $dbConnected = false;
            }

            $esHost = (string) Config::get('elasticsearch.host', 'http://127.0.0.1:9200');
            $esIndex = (string) Config::get('elasticsearch.index', 'games');
            $esAvailable = $this->searchService->isAvailable();

            return [
                'database' => [
                    'connected' => $dbConnected,
                    'driver' => $dbDriver,
                    'database' => $dbName,
                    'host' => $dbHost,
                    'port' => $dbPort,
                    'version' => $dbVersion,
                    'latency_ms' => $dbLatency,
                ],
                'elasticsearch' => [
                    'available' => $esAvailable,
                    'host' => $esHost,
                    'index' => $esIndex,
                ],
                'stack' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'environment' => app()->environment(),
                    'debug' => (bool) Config::get('app.debug', false),
                    'timezone' => (string) Config::get('app.timezone', 'UTC'),
                    'session_driver' => (string) Config::get('session.driver', 'database'),
                    'cache_driver' => (string) Config::get('cache.default', 'database'),
                ],
                'metrics' => [
                    'total_catalog_games' => Game::count(),
                    'user_library_games' => $user->userGames()->count(),
                    'total_platforms' => Platform::count(),
                    'total_libraries' => GameLibrary::count(),
                    'total_genres' => Genre::count(),
                    'total_users' => User::count(),
                    'total_user_games' => UserGame::count(),
                ],
            ];
        }

        // Usuário Comum: não executa sondagens de infraestrutura e protege a privacidade dos dados de terceiros
        return [
            'database' => null,
            'elasticsearch' => null,
            'stack' => null,
            'metrics' => [
                'total_catalog_games' => Game::count(),
                'user_library_games' => $user->userGames()->count(),
                'total_platforms' => $this->getPlatformsForUser($user)->count(),
                'total_libraries' => $this->getLibrariesForUser($user)->count(),
                'total_genres' => $this->getGenresForUser($user)->count(),
                'finished_games' => $user->userGames()->where('status', GameStatus::Finished)->count(),
                'playing_games' => $user->userGames()->where('status', GameStatus::Playing)->count(),
                'backlog_games' => $user->userGames()->where('status', GameStatus::Backlog)->count(),
                'dropped_games' => $user->userGames()->where('status', GameStatus::Dropped)->count(),
            ],
        ];
    }

    /**
     * Retorna as plataformas visíveis para o usuário (padrão do sistema e criadas por ele)
     *
     * @return Collection<int, Platform>
     */
    public function getPlatformsForUser(User $user): Collection
    {
        return Platform::where(function ($q) use ($user) {
            $q->where('is_custom', false)
                ->orWhere('user_id', $user->id);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Cria uma nova plataforma personalizada
     */
    public function createPlatform(User $user, string $name, string $color): Platform
    {
        $trimmedName = trim($name);
        $normalizedColor = ColorConverter::normalizeHex($color);

        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'platform-'.Str::random(6);
        $counter = 1;

        while (Platform::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return Platform::create([
            'name' => $trimmedName,
            'slug' => $slug,
            'color' => $normalizedColor,
            'is_custom' => true,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Atualiza uma plataforma
     *
     * @throws AuthorizationException
     */
    public function updatePlatform(User $user, Platform $platform, string $name, string $color): Platform
    {
        if (! $platform->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para editar esta plataforma.');
        }

        $trimmedName = trim($name);
        $normalizedColor = ColorConverter::normalizeHex($color);

        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'platform-'.Str::random(6);
        $counter = 1;

        while (Platform::where('slug', $slug)->where('id', '!=', $platform->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $platform->update([
            'name' => $trimmedName,
            'slug' => $slug,
            'color' => $normalizedColor,
        ]);

        return $platform;
    }

    /**
     * Exclui uma plataforma
     *
     * @throws AuthorizationException
     */
    public function deletePlatform(User $user, Platform $platform): void
    {
        if (! $platform->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para excluir esta plataforma.');
        }

        $platform->delete();
    }

    /**
     * Retorna as bibliotecas visíveis para o usuário (padrão do sistema e criadas por ele)
     *
     * @return Collection<int, GameLibrary>
     */
    public function getLibrariesForUser(User $user): Collection
    {
        return GameLibrary::where(function ($q) use ($user) {
            $q->where('is_custom', false)
                ->orWhere('user_id', $user->id);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Cria uma nova biblioteca personalizada para PC
     */
    public function createLibrary(User $user, string $name, string $color): GameLibrary
    {
        $trimmedName = trim($name);
        $normalizedColor = ColorConverter::normalizeHex($color);

        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'library-'.Str::random(6);
        $counter = 1;

        while (GameLibrary::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return GameLibrary::create([
            'name' => $trimmedName,
            'slug' => $slug,
            'color' => $normalizedColor,
            'is_custom' => true,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Atualiza uma biblioteca
     *
     * @throws AuthorizationException
     */
    public function updateLibrary(User $user, GameLibrary $library, string $name, string $color): GameLibrary
    {
        if (! $library->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para editar esta biblioteca.');
        }

        $trimmedName = trim($name);
        $normalizedColor = ColorConverter::normalizeHex($color);

        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'library-'.Str::random(6);
        $counter = 1;

        while (GameLibrary::where('slug', $slug)->where('id', '!=', $library->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $library->update([
            'name' => $trimmedName,
            'slug' => $slug,
            'color' => $normalizedColor,
        ]);

        return $library;
    }

    /**
     * Exclui uma biblioteca
     *
     * @throws AuthorizationException
     */
    public function deleteLibrary(User $user, GameLibrary $library): void
    {
        if (! $library->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para excluir esta biblioteca.');
        }

        $library->delete();
    }

    /**
     * Retorna os gêneros visíveis para o usuário (padrão do sistema e criados por ele)
     *
     * @return Collection<int, Genre>
     */
    public function getGenresForUser(User $user): Collection
    {
        return Genre::where(function ($q) use ($user) {
            $q->where('is_custom', false)
                ->orWhere('user_id', $user->id);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Cria um novo gênero personalizado
     */
    public function createGenre(User $user, string $name): Genre
    {
        $trimmedName = trim($name);
        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'genre-'.Str::random(6);
        $counter = 1;

        while (Genre::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return Genre::create([
            'name' => $trimmedName,
            'slug' => $slug,
            'is_custom' => true,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Atualiza um gênero
     *
     * @throws AuthorizationException
     */
    public function updateGenre(User $user, Genre $genre, string $name): Genre
    {
        if (! $genre->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para editar este gênero.');
        }

        $trimmedName = trim($name);
        $baseSlug = Str::slug($trimmedName);
        $slug = $baseSlug ?: 'genre-'.Str::random(6);
        $counter = 1;

        while (Genre::where('slug', $slug)->where('id', '!=', $genre->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $genre->update([
            'name' => $trimmedName,
            'slug' => $slug,
        ]);

        return $genre;
    }

    /**
     * Exclui um gênero
     *
     * @throws AuthorizationException
     */
    public function deleteGenre(User $user, Genre $genre): void
    {
        if (! $genre->canBeManagedBy($user)) {
            throw new AuthorizationException('Você não tem permissão para excluir este gênero.');
        }

        $genre->delete();
    }
}
