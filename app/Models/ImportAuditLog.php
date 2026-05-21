<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportAuditLog extends Model
{
    protected $fillable = [
        'import_type',
        'filename',
        'dry_run',
        'rows_total',
        'rows_ok',
        'rows_failed',
        'row_errors',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'dry_run' => 'boolean',
            'row_errors' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
