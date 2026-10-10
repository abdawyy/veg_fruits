<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteVisitor extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'last_path',
        'first_path',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'device_type',
        'country_code',
        'country',
        'region',
        'city',
        'geo_resolved_at',
        'ip_hash',
        'user_agent_hash',
        'first_seen_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'geo_resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** "City, Region, Country" with empty parts skipped; null when unknown. */
    public function getLocationLabelAttribute(): ?string
    {
        $parts = array_values(array_unique(array_filter([
            $this->city,
            $this->region,
            $this->country,
        ], fn ($part) => is_string($part) && $part !== '')));

        return $parts === [] ? null : implode(', ', $parts);
    }

    public static function activeSinceMinutes(int $minutes = 5): int
    {
        return self::query()->where('last_seen_at', '>=', now()->subMinutes($minutes))->count();
    }
}
