<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlatformFactory;
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
class Platform extends Model
{
    /** @use HasFactory<PlatformFactory> */
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
     * Cores oficiais da marca da plataforma
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
     * Resolve as cores oficiais da plataforma a partir do nome ou slug
     *
     * @return array{bg: string, text: string, border: string}
     */
    public static function resolveColors(string $name, string $slug): array
    {
        $slugLower = strtolower($slug);
        $nameLower = strtolower($name);

        if (str_contains($slugLower, 'switch') || str_contains($nameLower, 'switch') || str_contains($slugLower, 'nintendo')) {
            return ['bg' => '#E60012', 'text' => '#ffffff', 'border' => '#FF1A2D'];
        }
        if (str_contains($slugLower, 'playstation') || str_contains($nameLower, 'playstation') || str_contains($slugLower, 'ps')) {
            return ['bg' => '#003791', 'text' => '#ffffff', 'border' => '#0055DC'];
        }
        if (str_contains($slugLower, 'xbox') || str_contains($nameLower, 'xbox')) {
            return ['bg' => '#107C0F', 'text' => '#ffffff', 'border' => '#189A17'];
        }
        if (str_contains($slugLower, 'pc') || str_contains($nameLower, 'pc')) {
            return ['bg' => '#1D2C4B', 'text' => '#ffffff', 'border' => '#2A3F6D'];
        }

        return ['bg' => 'var(--bg-main)', 'text' => 'var(--text-main)', 'border' => 'var(--border-color)'];
    }

    /**
     * Usuário criador da plataforma personalizada
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Jogos disponíveis nesta plataforma
     *
     * @return BelongsToMany<Game, $this>
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_platform')->withTimestamps();
    }
}
