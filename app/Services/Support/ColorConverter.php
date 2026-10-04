<?php

declare(strict_types=1);

namespace App\Services\Support;

class ColorConverter
{
    /**
     * Valida se uma string é um código hexadecimal válido (ex: #182075, #fff, 182075)
     */
    public static function isValidHex(string $hex): bool
    {
        $cleaned = ltrim(trim($hex), '#');

        return (bool) preg_match('/^([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $cleaned);
    }

    /**
     * Normaliza a cor para o formato hexadecimal de 6 dígitos com hash (ex: #182075)
     */
    public static function normalizeHex(string $hex): string
    {
        $cleaned = ltrim(trim($hex), '#');

        if (strlen($cleaned) === 3) {
            $cleaned = $cleaned[0].$cleaned[0].$cleaned[1].$cleaned[1].$cleaned[2].$cleaned[2];
        }

        if (strlen($cleaned) !== 6) {
            return '#182075';
        }

        return '#'.strtoupper($cleaned);
    }

    /**
     * Converte código hexadecimal para formato RGB string: rgb(r, g, b)
     */
    public static function hexToRgb(string $hex): string
    {
        $normalized = self::normalizeHex($hex);
        $raw = ltrim($normalized, '#');

        $r = hexdec(substr($raw, 0, 2));
        $g = hexdec(substr($raw, 2, 2));
        $b = hexdec(substr($raw, 4, 2));

        return sprintf('rgb(%d, %d, %d)', $r, $g, $b);
    }

    /**
     * Converte código hexadecimal para formato HSL string: hsl(h, s%, l%)
     */
    public static function hexToHsl(string $hex): string
    {
        $normalized = self::normalizeHex($hex);
        $raw = ltrim($normalized, '#');

        $r = hexdec(substr($raw, 0, 2)) / 255;
        $g = hexdec(substr($raw, 2, 2)) / 255;
        $b = hexdec(substr($raw, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        $l = ($max + $min) / 2;

        if ($delta == 0.0) {
            $h = 0;
            $s = 0;
        } else {
            $s = $l > 0.5 ? $delta / (2.0 - $max - $min) : $delta / ($max + $min);

            if ($max === $r) {
                $h = (($g - $b) / $delta) + ($g < $b ? 6 : 0);
            } elseif ($max === $g) {
                $h = (($b - $r) / $delta) + 2;
            } else {
                $h = (($r - $g) / $delta) + 4;
            }

            $h /= 6;
        }

        $hDeg = (int) round($h * 360);
        $sPct = (int) round($s * 100);
        $lPct = (int) round($l * 100);

        return sprintf('hsl(%d, %d%%, %d%%)', $hDeg, $sPct, $lPct);
    }

    /**
     * Retorna array com todos os formatos de cor (HEX, RGB e HSL)
     *
     * @return array{hex: string, rgb: string, hsl: string}
     */
    public static function toAllFormats(string $hex): array
    {
        $normalized = self::normalizeHex($hex);

        return [
            'hex' => $normalized,
            'rgb' => self::hexToRgb($normalized),
            'hsl' => self::hexToHsl($normalized),
        ];
    }
}
