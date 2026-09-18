<?php

declare(strict_types=1);

namespace App\Enums;

enum AgeRating: string
{
    case Livre = 'Livre';
    case Age10 = '10+';
    case Age12 = '12+';
    case Age14 = '14+';
    case Age16 = '16+';
    case Age18 = '18+';

    /**
     * Rótulo humanizado oficial da classificação indicativa do Brasil (ClassInd)
     */
    public function label(): string
    {
        return match ($this) {
            self::Livre => 'Livre (Todos os públicos)',
            self::Age10 => '10+ (Não recomendado para menores de 10 anos)',
            self::Age12 => '12+ (Não recomendado para menores de 12 anos)',
            self::Age14 => '14+ (Não recomendado para menores de 14 anos)',
            self::Age16 => '16+ (Não recomendado para menores de 16 anos)',
            self::Age18 => '18+ (Não recomendado para menores de 18 anos)',
        };
    }

    /**
     * Rótulo curto para exibição em badges compactos
     */
    public function shortLabel(): string
    {
        return $this->value;
    }

    /**
     * Idade mínima recomendada
     */
    public function minimumAge(): int
    {
        return match ($this) {
            self::Livre => 0,
            self::Age10 => 10,
            self::Age12 => 12,
            self::Age14 => 14,
            self::Age16 => 16,
            self::Age18 => 18,
        };
    }

    /**
     * Cores oficiais da ClassInd (Brasil)
     *
     * @return array{bg: string, text: string, border: string}
     */
    public function colors(): array
    {
        return match ($this) {
            self::Livre => ['bg' => '#338933', 'text' => '#ffffff', 'border' => '#338933'],
            self::Age10 => ['bg' => '#2474B9', 'text' => '#ffffff', 'border' => '#2474B9'],
            self::Age12 => ['bg' => '#FFCC00', 'text' => '#000000', 'border' => '#FFCC00'],
            self::Age14 => ['bg' => '#DB772C', 'text' => '#ffffff', 'border' => '#DB772C'],
            self::Age16 => ['bg' => '#C90000', 'text' => '#ffffff', 'border' => '#C90000'],
            self::Age18 => ['bg' => '#000000', 'text' => '#ffffff', 'border' => '#333333'],
        };
    }

    /**
     * Tenta resolver a classificação a partir de variações de string (ex: '10', '10+', 'livre', '18')
     */
    public static function tryFromLenient(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $clean = trim($value);

        return match (strtolower($clean)) {
            'livre', 'l' => self::Livre,
            '10', '10+' => self::Age10,
            '12', '12+' => self::Age12,
            '14', '14+' => self::Age14,
            '16', '16+' => self::Age16,
            '18', '18+' => self::Age18,
            default => self::tryFrom($clean),
        };
    }
}
