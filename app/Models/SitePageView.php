<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePageView extends Model
{
    protected $fillable = [
        'session_id',
        'path',
        'referrer',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }
}
