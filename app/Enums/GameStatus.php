<?php

declare(strict_types=1);

namespace App\Enums;

enum GameStatus: string
{
    case Finished = 'finished';
    case Playing = 'playing';
    case Dropped = 'dropped';
    case Backlog = 'backlog';

    /**
     * Obter o rótulo humanizado em português
     */
    public function label(): string
    {
        return match ($this) {
            self::Finished => 'Finalizado',
            self::Playing => 'Em Andamento',
            self::Dropped => 'Desistido',
            self::Backlog => 'Backlog',
        };
    }

    /**
     * Obter a cor HEX associada ao status
     */
    public function colorHex(): string
    {
        return match ($this) {
            self::Finished => '#2e7d32',
            self::Playing => '#0288d1',
            self::Dropped => '#c62828',
            self::Backlog => '#f57f17',
        };
    }

    /**
     * Obter a variável CSS vinculada ao Design System
     */
    public function cssVariable(): string
    {
        return match ($this) {
            self::Finished => 'var(--status-finished)',
            self::Playing => 'var(--status-playing)',
            self::Dropped => 'var(--status-dropped)',
            self::Backlog => 'var(--status-backlog)',
        };
    }
}
