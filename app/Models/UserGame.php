<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GameStatus;
use Database\Factories\UserGameFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $game_id
 * @property GameStatus $status
 * @property float $hours_played
 * @property float|null $rating
 * @property string|null $review
 * @property Carbon|null $finished_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'user_id',
    'game_id',
    'status',
    'hours_played',
    'rating',
    'review',
    'finished_at',
])]
class UserGame extends Model
{
    /** @use HasFactory<UserGameFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => GameStatus::class,
            'hours_played' => 'decimal:2',
            'rating' => 'decimal:1',
            'finished_at' => 'datetime',
        ];
    }

    /**
     * Usuário dono do registro
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Jogo associado
     *
     * @return BelongsTo<Game, $this>
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
