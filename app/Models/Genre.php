<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_custom
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'slug', 'is_custom', 'user_id'])]
class Genre extends Model
{
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
     * Usuário criador do gênero personalizado
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determina se o usuário fornecido possui permissão para editar ou excluir este gênero
     */
    public function canBeManagedBy(User $user): bool
    {
        // Se for item nativo do sistema, somente administradores podem gerenciar
        if (! $this->is_custom || $this->user_id === null) {
            return $user->isAdmin();
        }

        // Se for item personalizado, somente o próprio criador pode gerenciar
        return $this->user_id === $user->id;
    }
}
