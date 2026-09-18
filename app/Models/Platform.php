<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SystemPlatform;
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
 * @property-read SystemPlatform|null $system_platform
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
     * Enum da plataforma padrão do sistema, se aplicável
     *
     * @return Attribute<SystemPlatform|null, never>
     */
    protected function systemPlatform(): Attribute
    {
        return Attribute::make(
            get: fn (): ?SystemPlatform => SystemPlatform::tryFromSlugOrName($this->name, $this->slug),
        );
    }

    /**
     * Resolve as cores oficiais da plataforma a partir do nome ou slug
     *
     * @return array{bg: string, text: string, border: string}
     */
    public static function resolveColors(string $name, string $slug): array
    {
        return SystemPlatform::tryFromSlugOrName($name, $slug)?->brandColors()
            ?? ['bg' => 'var(--bg-main)', 'text' => 'var(--text-main)', 'border' => 'var(--border-color)'];
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
