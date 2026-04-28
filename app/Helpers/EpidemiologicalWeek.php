<?php

namespace App\Helpers;

use Carbon\Carbon;

class EpidemiologicalWeek
{
    /**
     * Semana Epidemiológica brasileira (SVS/FUNASA):
     * inicia no domingo; SE1 é a semana que contém o dia 4 de janeiro.
     */
    public static function getStartOfSE1(int $year): Carbon
    {
        return Carbon::create($year, 1, 4)->startOfWeek(Carbon::SUNDAY);
    }

    public static function fromDate(Carbon $date): int
    {
        $se1 = self::getStartOfSE1($date->year);

        if ($date->lt($se1)) {
            $prevSe1 = self::getStartOfSE1($date->year - 1);
            return (int) $date->diffInWeeks($prevSe1) + 1;
        }

        $nextSe1 = self::getStartOfSE1($date->year + 1);
        if ($date->gte($nextSe1)) {
            return 1;
        }

        return (int) $date->diffInWeeks($se1) + 1;
    }

    public static function getYearForDate(Carbon $date): int
    {
        $se1 = self::getStartOfSE1($date->year);
        if ($date->lt($se1)) {
            return $date->year - 1;
        }
        if ($date->gte(self::getStartOfSE1($date->year + 1))) {
            return $date->year + 1;
        }
        return $date->year;
    }

    public static function getRange(int $week, int $year): array
    {
        $start = self::getStartOfSE1($year)->addWeeks($week - 1)->startOfDay();
        $end = $start->copy()->addDays(6)->endOfDay();
        return ['start' => $start, 'end' => $end];
    }

    public static function getCurrent(): int
    {
        return self::fromDate(Carbon::now());
    }

    public static function getCurrentYear(): int
    {
        return self::getYearForDate(Carbon::now());
    }
}
