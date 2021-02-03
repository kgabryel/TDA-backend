<?php

namespace App\Utils;

use App\Exceptions\InvalidURLException;

abstract class URLUtils
{
    public static function getQueryParameter(string $url, string $parameterName): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidURLException("{$url} is not a valid URL.");
        }
        $parts = parse_url($url);
        if (!isset($parts['query'])) {
            throw new InvalidURLException("{$url} does not contain a {$parameterName} parameter.");
        }
        parse_str($parts['query'], $parameters);
        if (!isset($parameters[$parameterName])) {
            throw new InvalidURLException("{$url} does not contain a {$parameterName} parameter.");
        }
        return $parameters[$parameterName];
    }

    public static function queryParameterExists(string $url, string $parameterName): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidURLException("{$url} is not a valid URL.");
        }
        $parts = parse_url($url);
        if (!isset($parts['query'])) {
            return false;
        }
        parse_str($parts['query'], $parameters);
        return isset($parameters[$parameterName]);
    }
}
