<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduceBoxView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'produce_box_id',
        'session_id',
        'user_id',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public function produceBox(): BelongsTo
    {
        return $this->belongsTo(ProduceBox::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
