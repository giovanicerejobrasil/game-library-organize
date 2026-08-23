<?php

declare(strict_types=1);

namespace App\Enums;

enum ThemeMode: string
{
    case Dark = 'dark';
    case Light = 'light';

    /**
     * Obter o rótulo legível do tema
     */
    public function label(): string
    {
        return match ($this) {
            self::Dark => 'Modo Escuro',
            self::Light => 'Modo Claro',
        };
    }
}
