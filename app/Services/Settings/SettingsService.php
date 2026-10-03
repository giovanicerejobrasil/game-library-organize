<?php

declare(strict_types=1);

namespace App\Services\Settings;

use App\Enums\ThemeMode;
use App\Models\Game;
use App\Models\GameLibrary;
use App\Models\Platform;
use App\Models\User;
use App\Services\Elasticsearch\GameSearchService;
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
     * Coleta informações e diagnósticos reais do sistema e infraestrutura
     *
     * @return array{
     *     database: array{
     *         connected: bool,
     *         driver: string,
     *         database: string,
     *         host: string,
     *         port: string|int,
     *         version: string,
     *         latency_ms: float
     *     },
     *     elasticsearch: array{
     *         available: bool,
     *         host: string,
     *         index: string
     *     },
     *     stack: array{
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
     *         total_libraries: int
     *     }
     * }
     */
    public function getSystemDiagnostics(User $user): array
    {
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
            ],
        ];
    }
}
