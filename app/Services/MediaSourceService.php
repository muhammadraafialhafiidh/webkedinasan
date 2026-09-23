<?php

namespace App\Services;

class MediaSourceService
{
    /**
     * Whitelist domain untuk Google Drive
     */
    public const GOOGLE_DRIVE_DOMAINS = [
        'drive.google.com',
        'docs.google.com',
    ];

    /**
     * Whitelist domain untuk Instagram
     */
    public const INSTAGRAM_DOMAINS = [
        'instagram.com',
        'www.instagram.com',
        'm.instagram.com',
    ];

    /**
     * Validasi apakah URL merupakan tautan Google Drive yang didukung
     */
    public static function isValidGoogleDriveUrl(?string $url): bool
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        if (!in_array($host, self::GOOGLE_DRIVE_DOMAINS, true)) {
            return false;
        }

        return !empty(self::extractGoogleDriveFileId($url));
    }

    /**
     * Ekstrak File ID dari URL Google Drive
     */
    public static function extractGoogleDriveFileId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Format 1: drive.google.com/file/d/FILE_ID/view... atau docs.google.com/file/d/FILE_ID/...
        if (preg_match('/(?:drive|docs)\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return $matches[1];
        }

        // Format 2: drive.google.com/open?id=FILE_ID atau uc?id=FILE_ID
        if (preg_match('/(?:drive|docs)\.google\.com\/(?:open|uc)\?(?:[^#]*&)?id=([a-zA-Z0-9_-]+)/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Buat URL embed resmi/standar untuk Google Drive
     */
    public static function getGoogleDriveEmbedUrl(?string $url): ?string
    {
        if (!self::isValidGoogleDriveUrl($url)) {
            return null;
        }

        $fileId = self::extractGoogleDriveFileId($url);
        if ($fileId) {
            return 'https://drive.google.com/file/d/' . $fileId . '/preview';
        }

        return null;
    }

    /**
     * Validasi apakah URL merupakan tautan postingan Instagram yang didukung
     */
    public static function isValidInstagramUrl(?string $url): bool
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        if (!in_array($host, self::INSTAGRAM_DOMAINS, true)) {
            return false;
        }

        return !empty(self::extractInstagramShortcode($url));
    }

    /**
     * Ekstrak shortcode dari URL postingan Instagram (p, reel, reels, tv)
     */
    public static function extractInstagramShortcode(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match('/instagram\.com\/(?:p|reels?|tv)\/([A-Za-z0-9_-]+)/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Buat URL embed resmi/standar untuk Instagram
     */
    public static function getInstagramEmbedUrl(?string $url): ?string
    {
        if (!self::isValidInstagramUrl($url)) {
            return null;
        }

        $shortcode = self::extractInstagramShortcode($url);
        if ($shortcode) {
            return 'https://www.instagram.com/p/' . $shortcode . '/embed';
        }

        return null;
    }
}
