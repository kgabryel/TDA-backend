<?php

namespace App\Utils;

use App\Exceptions\DateException;
use Carbon\Carbon;

abstract class DateUtils
{
    public const JOB_DAY = 25;
    public const JOB_HOUR = 1;

    public static function addInterval(int $interval, string $intervalType, Carbon $date): Carbon
    {
        switch ($intervalType) {
            case 'day':
                return $date->copy()
                    ->addDays($interval);
            case 'week':
                return $date->copy()
                    ->addWeeks($interval);
            case 'month':
                return $date->copy()
                    ->addMonths($interval);
        }
        throw new DateException(sprintf('Invalid interval type (%s)', $intervalType));
    }

    public static function getNextMonthEnd(): Carbon
    {
        return Carbon::now()
            ->addMonth()
            ->endOfMonth();
    }

    /**
     * @param int $interval
     * @param string $intervalType
     * @param Carbon $start
     * @param Carbon|null $stop
     *
     * @return Carbon[]
     * @throws DateException
     */
    public static function getDatesTillNextMonthEnd(
        int $interval, string $intervalType, Carbon $start, ?Carbon $stop
    ): array
    {
        $endOfNextMonth = self::getNextMonthEnd();
        if ($stop === null || $endOfNextMonth < $stop) {
            $stop = $endOfNextMonth;
        }
        return self::getDates($interval, $intervalType, $start, $stop);
    }

    /**
     * @param int $interval
     * @param string $intervalType
     * @param Carbon $start
     * @param Carbon $stop
     *
     * @return array
     * @throws DateException
     */
    public static function getDates(
        int $interval, string $intervalType, Carbon $start, Carbon $stop
    ): array
    {
        $dates = [];
        while ($start <= $stop) {
            $dates[] = $start;
            $start = self::addInterval($interval, $intervalType, $start);
        }
        return $dates;
    }

    public static function modifyDate(Carbon $date, int $time): Carbon
    {
        return $date->copy()
            ->addSeconds($time);
    }

    public static function jobDone(): bool
    {
        return Carbon::now() > Carbon::now()
                ->setDay(self::JOB_DAY)
                ->setHour(self::JOB_HOUR)
                ->setMinute(0)
                ->setSecond(0);
    }

    /** @return string[] */
    public static function getAvailableIntervalTypes(): array
    {
        return [
            'day',
            'week',
            'month'
        ];
    }
}
