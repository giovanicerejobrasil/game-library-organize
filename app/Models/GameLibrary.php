<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\GameLibraryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property bool $is_custom
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array{bg: string, text: string, border: string} $brand_colors
 */
#[Fillable(['name', 'slug', 'icon', 'is_custom', 'user_id'])]
class GameLibrary extends Model
{
    /** @use HasFactory<GameLibraryFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
        ];
    }

    /**
     * Cores oficiais da marca da biblioteca digital
     *
     * @return Attribute<array{bg: string, text: string, border: string}, never>
     */
    protected function brandColors(): Attribute
    {
        return Attribute::make(
            get: fn (): array => self::resolveColors($this->name, $this->slug),
        );
    }

    /**
     * Resolve as cores oficiais da biblioteca digital a partir do nome ou slug
     *
     * @return array{bg: string, text: string, border: string}
     */
    public static function resolveColors(string $name, string $slug): array
    {
        $slugLower = strtolower($slug);
        $nameLower = strtolower($name);

        if (str_contains($slugLower, 'steam') || str_contains($nameLower, 'steam')) {
            return ['bg' => '#1D2C4B', 'text' => '#ffffff', 'border' => '#2A3F6D'];
        }
        if (str_contains($slugLower, 'epic') || str_contains($nameLower, 'epic')) {
            return ['bg' => '#000000', 'text' => '#ffffff', 'border' => '#333333'];
        }
        if (str_contains($slugLower, 'luna') || str_contains($nameLower, 'luna') || str_contains($slugLower, 'amazon') || str_contains($nameLower, 'amazon')) {
            return ['bg' => '#8E45F7', 'text' => '#ffffff', 'border' => '#A368F8'];
        }
        if (str_contains($slugLower, 'ea') || str_contains($nameLower, 'ea')) {
            return ['bg' => '#FF4747', 'text' => '#ffffff', 'border' => '#FF6B6B'];
        }
        if (str_contains($slugLower, 'gog') || str_contains($nameLower, 'gog')) {
            return ['bg' => '#981EEA', 'text' => '#ffffff', 'border' => '#B047F0'];
        }
        if (str_contains($slugLower, 'rockstar') || str_contains($nameLower, 'rockstar')) {
            return ['bg' => '#F7A600', 'text' => '#000000', 'border' => '#FFB81A'];
        }
        if (str_contains($slugLower, 'ubisoft') || str_contains($nameLower, 'ubisoft')) {
            return ['bg' => '#3B4984', 'text' => '#ffffff', 'border' => '#4E5FA8'];
        }
        if (str_contains($slugLower, 'xbox') || str_contains($nameLower, 'xbox')) {
            return ['bg' => '#107C0F', 'text' => '#ffffff', 'border' => '#189A17'];
        }

        return ['bg' => 'var(--bg-main)', 'text' => 'var(--text-main)', 'border' => 'var(--border-color)'];
    }

    /**
     * Usuário criador da biblioteca personalizada
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Jogos disponíveis nesta biblioteca
     *
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_library', 'library_id', 'game_id')->withTimestamps();
    }
}
