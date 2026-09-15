<?php

namespace App\Helpers;

class YouTube
{
    public static function id(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host);

        if ($host === 'youtu.be') {
            return trim((string) parse_url($url, PHP_URL_PATH), '/');
        }

        if ($host === 'youtube.com' || str_ends_with($host, '.youtube.com')) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            if (! empty($query['v'])) {
                return $query['v'];
            }

            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
            foreach (['embed/', 'shorts/', 'live/'] as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    return explode('/', substr($path, strlen($prefix)))[0] ?: null;
                }
            }
        }

        return null;
    }

    public static function thumbnail(?string $url): ?string
    {
        $id = self::id($url);

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    public static function embed(?string $url): ?string
    {
        $id = self::id($url);

        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }
}
