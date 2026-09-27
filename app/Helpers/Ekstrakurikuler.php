<?php

namespace App\Helpers;

class Ekstrakurikuler
{
    public static function url(?string $name): ?string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }

        $options = config('app.ekstrakurikuler', []);
        $normalizedName = mb_strtolower($name);

        foreach ($options as $option => $url) {
            if (mb_strtolower(trim($option)) === $normalizedName) {
                return self::normalizeUrl($url);
            }
        }

        if (str_contains($normalizedName, 'pramuka')) {
            return self::normalizeUrl($options['Pramuka SMK Negeri 1 Bangsri'] ?? null);
        }

        return null;
    }

    private static function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        return $url !== '' ? $url : null;
    }
}