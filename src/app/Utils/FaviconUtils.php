<?php

namespace App\Utils;

use App\Exceptions\InvalidURLException;
use Illuminate\Support\Facades\Http;

abstract class FaviconUtils
{
    public static function getAddress(string $url): ?string
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidURLException("{$url} is not a valid URL.");
        }
        $parts = parse_url($url);
        if (isset($parts['fragment'])) {
            $url = str_replace('#' . $parts['fragment'], '', $url);
        }
        if (isset($parts['query'])) {
            $url = str_replace('?' . $parts['query'], '', $url);
        }
        if (isset($parts['path']) && $parts['path'] !== '/') {
            $url = str_replace($parts['path'], '', $url);
        }
        $response = Http::get($url . '/favicon.ico');
        if ($response->status() === 200 && $response->body() !== '') {
            return $url . '/favicon.ico';
        }
        return null;
    }
}
