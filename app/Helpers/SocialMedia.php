<?php

namespace App\Helpers;

class SocialMedia
{
    public static function platform(?string $url): ?string
    {
        $host = preg_replace('/^www\./', '', strtolower((string) parse_url((string) $url, PHP_URL_HOST)));

        return match (true) {
            $host === 'youtube.com', str_ends_with($host, '.youtube.com'), $host === 'youtu.be' => 'youtube',
            $host === 'tiktok.com', str_ends_with($host, '.tiktok.com') => 'tiktok',
            $host === 'instagram.com', str_ends_with($host, '.instagram.com') => 'instagram',
            default => null,
        };
    }

    public static function embed(?string $url): ?string
    {
        return match (self::platform($url)) {
            'youtube' => YouTube::embed($url),
            'tiktok' => self::tiktokEmbed($url),
            'instagram' => self::instagramEmbed($url),
            default => null,
        };
    }

    private static function tiktokEmbed(string $url): ?string
    {
        if (! preg_match('#/@[^/]+/video/(\d+)#', (string) parse_url($url, PHP_URL_PATH), $matches)) {
            return null;
        }

        return 'https://www.tiktok.com/embed/v2/' . $matches[1];
    }

    private static function instagramEmbed(string $url): ?string
    {
        if (! preg_match('#/(?:p|reel|tv)/([^/?#]+)#', (string) parse_url($url, PHP_URL_PATH), $matches)) {
            return null;
        }

        return 'https://www.instagram.com/' . strtok((string) parse_url($url, PHP_URL_PATH), '/') . '/' . $matches[1] . '/embed/captioned/';
    }
}
