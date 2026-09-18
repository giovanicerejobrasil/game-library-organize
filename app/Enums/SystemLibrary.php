<?php

declare(strict_types=1);

namespace App\Enums;

enum SystemLibrary: string
{
    case Steam = 'steam';
    case EpicGames = 'epic-games';
    case Gog = 'gog';
    case UbisoftConnect = 'ubisoft-connect';
    case EaApp = 'ea-app';
    case Rockstar = 'rockstar-launcher';
    case XboxPc = 'xbox-pc';
    case AmazonLuna = 'amazon-games-luna';

    /**
     * Nome oficial da biblioteca digital
     */
    public function label(): string
    {
        return match ($this) {
            self::Steam => 'Steam',
            self::EpicGames => 'Epic Games',
            self::Gog => 'GOG',
            self::UbisoftConnect => 'Ubisoft Connect',
            self::EaApp => 'EA App',
            self::Rockstar => 'Rockstar Launcher',
            self::XboxPc => 'Xbox PC',
            self::AmazonLuna => 'Amazon Games / Amazon Luna',
        };
    }

    /**
     * Ícone oficial padrão
     */
    public function icon(): string
    {
        return match ($this) {
            self::Steam => 'steam',
            self::EpicGames => 'epic',
            self::Gog => 'gog',
            self::UbisoftConnect => 'ubisoft',
            self::EaApp => 'ea',
            self::Rockstar => 'rockstar',
            self::XboxPc => 'xbox',
            self::AmazonLuna => 'amazon',
        };
    }

    /**
     * Cores oficiais da marca da biblioteca digital
     *
     * @return array{bg: string, text: string, border: string}
     */
    public function brandColors(): array
    {
        return match ($this) {
            self::Steam => ['bg' => '#1D2C4B', 'text' => '#ffffff', 'border' => '#2A3F6D'],
            self::EpicGames => ['bg' => '#000000', 'text' => '#ffffff', 'border' => '#333333'],
            self::AmazonLuna => ['bg' => '#8E45F7', 'text' => '#ffffff', 'border' => '#A368F8'],
            self::EaApp => ['bg' => '#FF4747', 'text' => '#ffffff', 'border' => '#FF6B6B'],
            self::Gog => ['bg' => '#981EEA', 'text' => '#ffffff', 'border' => '#B047F0'],
            self::Rockstar => ['bg' => '#F7A600', 'text' => '#000000', 'border' => '#FFB81A'],
            self::UbisoftConnect => ['bg' => '#3B4984', 'text' => '#ffffff', 'border' => '#4E5FA8'],
            self::XboxPc => ['bg' => '#107C0F', 'text' => '#ffffff', 'border' => '#189A17'],
        };
    }

    /**
     * Identifica a biblioteca padrão do sistema a partir de nome ou slug
     */
    public static function tryFromSlugOrName(string $name, string $slug): ?self
    {
        $direct = self::tryFrom($slug);
        if ($direct) {
            return $direct;
        }

        $slugLower = strtolower($slug);
        $nameLower = strtolower($name);

        if (str_contains($slugLower, 'steam') || str_contains($nameLower, 'steam')) {
            return self::Steam;
        }
        if (str_contains($slugLower, 'epic') || str_contains($nameLower, 'epic')) {
            return self::EpicGames;
        }
        if (str_contains($slugLower, 'luna') || str_contains($nameLower, 'luna') || str_contains($slugLower, 'amazon') || str_contains($nameLower, 'amazon')) {
            return self::AmazonLuna;
        }
        if (str_contains($slugLower, 'ea') || str_contains($nameLower, 'ea')) {
            return self::EaApp;
        }
        if (str_contains($slugLower, 'gog') || str_contains($nameLower, 'gog')) {
            return self::Gog;
        }
        if (str_contains($slugLower, 'rockstar') || str_contains($nameLower, 'rockstar')) {
            return self::Rockstar;
        }
        if (str_contains($slugLower, 'ubisoft') || str_contains($nameLower, 'ubisoft')) {
            return self::UbisoftConnect;
        }
        if (str_contains($slugLower, 'xbox') || str_contains($nameLower, 'xbox')) {
            return self::XboxPc;
        }

        return null;
    }
}
