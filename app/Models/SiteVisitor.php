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
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function activeSinceMinutes(int $minutes = 5): int
    {
        return self::query()->where('last_seen_at', '>=', now()->subMinutes($minutes))->count();
    }
}
