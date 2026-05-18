<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ContentString extends Model
{
    protected $fillable = [
        'key',
        'value_en',
        'value_ar',
        'group',
        'admin_note',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('content_strings_map');
        });

        static::deleted(function (): void {
            Cache::forget('content_strings_map');
        });
    }
}
