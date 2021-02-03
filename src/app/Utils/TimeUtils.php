<?php

namespace App\Utils;

abstract class TimeUtils
{
    public static function roundToFullMinutes(int $seconds): int
    {
        $isNegative = $seconds < 0;
        if ($isNegative) {
            $seconds *= -1;
        }
        $seconds = floor($seconds / 60) * 60;
        if ($isNegative) {
            $seconds *= -1;
        }
        return $seconds;
    }
}
