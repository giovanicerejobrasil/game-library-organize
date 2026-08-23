<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $cover_image
 * @property string|null $background_image
 * @property string|null $synopsis
 * @property int|null $release_year
 * @property string|null $developer
 * @property string|null $publisher
 * @property string|null $trailer_url
 * @property bool $is_franchise
 * @property string|null $franchise_name
 * @property string|null $age_rating
 * @property array<string, string>|null $purchase_links
 * @property array<int, string>|null $genre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'title',
    'slug',
    'cover_image',
    'background_image',
    'synopsis',
    'release_year',
    'developer',
    'publisher',
    'trailer_url',
    'is_franchise',
    'franchise_name',
    'age_rating',
    'purchase_links',
    'genre',
])]
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_franchise' => 'boolean',
            'release_year' => 'integer',
            'purchase_links' => 'array',
            'genre' => 'array',
        ];
    }

    /**
     * Plataformas onde o jogo está disponível
     *
     * @return BelongsToMany<Platform, $this>
     */
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'game_platform')->withTimestamps();
    }

    /**
     * Bibliotecas digitais onde o jogo está disponível
     *
     * @return BelongsToMany<GameLibrary, $this>
     */
    public function libraries(): BelongsToMany
    {
        return $this->belongsToMany(GameLibrary::class, 'game_library', 'game_id', 'library_id')->withTimestamps();
    }

    /**
     * Progresso de todos os usuários para este jogo
     *
     * @return HasMany<UserGame, $this>
     */
    public function userGames(): HasMany
    {
        return $this->hasMany(UserGame::class);
    }

    /**
     * Usuários que possuem este jogo na biblioteca pessoal
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_games')
            ->withPivot(['id', 'status', 'hours_played', 'rating', 'review', 'finished_at'])
            ->withTimestamps();
    }
}
