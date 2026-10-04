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
 * @property string|null $color
 * @property bool $is_custom
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array{bg: string, text: string, border: string} $brand_colors
 * @property-read string $resolved_color
 * @property-read SystemPlatform|null $system_platform
 */
#[Fillable(['name', 'slug', 'icon', 'color', 'is_custom', 'user_id'])]
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
     * Retorna a cor resolvida em HEX
     */
    protected function resolvedColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->color
                ?? (SystemPlatform::tryFromSlugOrName($this->name, $this->slug)?->brandColors()['bg'] ?? '#182075'),
        );
    }

    /**
     * Cores oficiais da marca da plataforma
     *
     * @return Attribute<array{bg: string, text: string, border: string}, never>
     */
    protected function brandColors(): Attribute
    {
        return Attribute::make(
            get: function (): array {
                if (! empty($this->color)) {
                    return [
                        'bg' => $this->color,
                        'text' => '#ffffff',
                        'border' => $this->color,
                    ];
                }

                return self::resolveColors($this->name, $this->slug);
            },
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
     * Determina se o usuário fornecido possui permissão para editar ou excluir esta plataforma
     */
    public function canBeManagedBy(User $user): bool
    {
        if (! $this->is_custom || $this->user_id === null) {
            return $user->isAdmin();
        }

        return $this->user_id === $user->id;
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
