<?php

declare(strict_types=1);

namespace App\Enums;

enum SystemPlatform: string
{
    case Switch = 'nintendo-switch';
    case Xbox = 'xbox-microsoft';
    case PlayStation = 'playstation-sony';
    case Pc = 'pc';

    /**
     * Nome oficial da plataforma
     */
    public function label(): string
    {
        return match ($this) {
            self::Switch => 'Nintendo Switch',
            self::Xbox => 'Xbox (Microsoft)',
            self::PlayStation => 'PlayStation (Sony)',
            self::Pc => 'PC',
        };
    }

    /**
     * Ícone oficial da plataforma
     */
    public function icon(): string
    {
        return match ($this) {
            self::Switch => 'switch',
            self::Xbox => 'xbox',
            self::PlayStation => 'playstation',
            self::Pc => 'pc',
        };
    }

    /**
     * Cores oficiais da marca da plataforma
     *
     * @return array{bg: string, text: string, border: string}
     */
    public function brandColors(): array
    {
        return match ($this) {
            self::Switch => ['bg' => '#E60012', 'text' => '#ffffff', 'border' => '#FF1A2D'],
            self::PlayStation => ['bg' => '#003791', 'text' => '#ffffff', 'border' => '#0055DC'],
            self::Xbox => ['bg' => '#107C0F', 'text' => '#ffffff', 'border' => '#189A17'],
            self::Pc => ['bg' => '#1D2C4B', 'text' => '#ffffff', 'border' => '#2A3F6D'],
        };
    }

    /**
     * Identifica a plataforma padrão do sistema a partir de nome ou slug
     */
    public static function tryFromSlugOrName(string $name, string $slug): ?self
    {
        $direct = self::tryFrom($slug);
        if ($direct) {
            return $direct;
        }

        $slugLower = strtolower($slug);
        $nameLower = strtolower($name);

        if (str_contains($slugLower, 'switch') || str_contains($nameLower, 'switch') || str_contains($slugLower, 'nintendo')) {
            return self::Switch;
        }
        if (str_contains($slugLower, 'playstation') || str_contains($nameLower, 'playstation') || str_contains($slugLower, 'ps')) {
            return self::PlayStation;
        }
        if (str_contains($slugLower, 'xbox') || str_contains($nameLower, 'xbox')) {
            return self::Xbox;
        }
        if (str_contains($slugLower, 'pc') || str_contains($nameLower, 'pc')) {
            return self::Pc;
        }

        return null;
    }
}
