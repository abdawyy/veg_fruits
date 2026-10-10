<?php

namespace App\Support\Analytics;

use Illuminate\Support\Carbon;

/** Reporting window chosen with the dashboard "Period" filter. */
final class DashboardPeriod
{
    public const DEFAULT = '30';

    private function __construct(public readonly string $key) {}

    public static function from(mixed $key): self
    {
        $key = is_scalar($key) ? (string) $key : '';

        return new self(array_key_exists($key, self::options()) ? $key : self::DEFAULT);
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return [
            'today' => __('Today'),
            '7' => __('Last 7 days'),
            '30' => __('Last 30 days'),
            '90' => __('Last 90 days'),
            '365' => __('Last 12 months'),
        ];
    }

    public function label(): string
    {
        return self::options()[$this->key];
    }

    public function start(): Carbon
    {
        if ($this->key === 'today') {
            return now(TimeBuckets::timezone())->startOfDay()->setTimezone(config('app.timezone'));
        }

        return now()->subDays((int) $this->key);
    }

    public function end(): Carbon
    {
        return now();
    }

    /**
     * Comparison window: yesterday up to the same time for "today",
     * otherwise the same-length window immediately before this one.
     */
    public function previousStart(): Carbon
    {
        $start = $this->start();

        if ($this->isToday()) {
            return $start->copy()->subDay();
        }

        return $start->copy()->subSeconds((int) abs($start->diffInSeconds($this->end())));
    }

    public function previousEnd(): Carbon
    {
        return $this->isToday() ? $this->end()->subDay() : $this->start();
    }

    public function comparisonLabel(): string
    {
        return $this->isToday() ? __('vs yesterday') : __('vs previous period');
    }

    public function isToday(): bool
    {
        return $this->key === 'today';
    }

    /** Trend buckets: hours for today, months for a year, days otherwise. */
    public function granularity(): string
    {
        return match ($this->key) {
            'today' => 'hour',
            '365' => 'month',
            default => 'day',
        };
    }
}
