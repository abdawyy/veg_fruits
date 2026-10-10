<?php

namespace App\Support\Analytics;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * Hour-of-day / weekday counts in the reporting timezone.
 *
 * Rows are grouped per stored hour in SQL (small result set), then each hour bucket
 * is shifted to the reporting timezone in PHP — exact for whole-hour offsets (Egypt, incl. DST).
 */
final class TimeBuckets
{
    /** Saturday-first week, as used in Egypt (Carbon dayOfWeek: 0 = Sunday). */
    public const WEEKDAY_ORDER = [6, 0, 1, 2, 3, 4, 5];

    public static function timezone(): string
    {
        return (string) config('analytics.timezone', 'Africa/Cairo');
    }

    /**
     * @return array<int, int> 24 entries, hour => count
     */
    public static function countByHour(EloquentBuilder|QueryBuilder $query, string $column): array
    {
        $counts = array_fill(0, 24, 0);

        foreach (self::hourBuckets($query, $column) as $moment => $count) {
            $counts[self::toReportingTime($moment)->hour] += $count;
        }

        return $counts;
    }

    /**
     * @return array<int, int> 7 entries in WEEKDAY_ORDER, Carbon dayOfWeek => count
     */
    public static function countByWeekday(EloquentBuilder|QueryBuilder $query, string $column): array
    {
        $counts = array_fill_keys(self::WEEKDAY_ORDER, 0);

        foreach (self::hourBuckets($query, $column) as $moment => $count) {
            $counts[self::toReportingTime($moment)->dayOfWeek] += $count;
        }

        return $counts;
    }

    /** @return list<string> "00:00" … "23:00" */
    public static function hourLabels(): array
    {
        return array_map(fn (int $h): string => sprintf('%02d:00', $h), range(0, 23));
    }

    /** @return list<string> Translated weekday names in WEEKDAY_ORDER */
    public static function weekdayLabels(): array
    {
        return array_map(fn (int $dow): string => self::weekdayName($dow), self::WEEKDAY_ORDER);
    }

    public static function weekdayName(int $dayOfWeek): string
    {
        return Carbon::now()->locale(app()->getLocale())->startOfWeek(Carbon::SUNDAY)->addDays($dayOfWeek)->translatedFormat('l');
    }

    public static function hourRangeLabel(int $hour): string
    {
        return sprintf('%02d:00–%02d:00', $hour, ($hour + 1) % 24);
    }

    /**
     * Index of the largest count, or null when every count is zero.
     *
     * @param  array<int, int>  $counts
     */
    public static function peak(array $counts): ?int
    {
        if ($counts === [] || max($counts) === 0) {
            return null;
        }

        return (int) array_search(max($counts), $counts, true);
    }

    /**
     * @return array<string, int> "Y-m-d H:00:00" (app timezone) => count
     */
    private static function hourBuckets(EloquentBuilder|QueryBuilder $query, string $column): array
    {
        $driver = $query->getConnection()->getDriverName();
        $expression = match ($driver) {
            'pgsql' => "to_char({$column}, 'YYYY-MM-DD HH24:00:00')",
            'sqlite' => "strftime('%Y-%m-%d %H:00:00', {$column})",
            'sqlsrv' => "FORMAT({$column}, 'yyyy-MM-dd HH:00:00')",
            default => "DATE_FORMAT({$column}, '%Y-%m-%d %H:00:00')",
        };

        return (clone $query)
            ->reorder()
            ->whereNotNull($column)
            ->selectRaw("{$expression} as hour_bucket, COUNT(*) as aggregate")
            ->groupBy('hour_bucket')
            ->pluck('aggregate', 'hour_bucket')
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    private static function toReportingTime(string $moment): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $moment, config('app.timezone'))
            ->setTimezone(self::timezone());
    }
}
