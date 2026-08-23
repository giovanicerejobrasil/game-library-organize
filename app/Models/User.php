<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ThemeMode;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property ThemeMode|string $theme
 * @property string $brand_primary
 * @property string $brand_secondary
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'theme', 'brand_primary', 'brand_secondary'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'theme' => ThemeMode::class,
        ];
    }

    /**
     * Jogos vinculados e progresso do usuário
     *
     * @return HasMany<UserGame, $this>
     */
    public function userGames(): HasMany
    {
        return $this->hasMany(UserGame::class);
    }

    /**
     * Acervo de jogos do usuário
     *
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'user_games')
            ->withPivot(['id', 'status', 'hours_played', 'rating', 'review', 'finished_at'])
            ->withTimestamps();
    }

    /**
     * Plataformas personalizadas criadas pelo usuário
     *
     * @return HasMany<Platform, $this>
     */
    public function customPlatforms(): HasMany
    {
        return $this->hasMany(Platform::class);
    }

    /**
     * Bibliotecas personalizadas criadas pelo usuário
     *
     * @return HasMany<GameLibrary, $this>
     */
    public function customLibraries(): HasMany
    {
        return $this->hasMany(GameLibrary::class);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }
}
