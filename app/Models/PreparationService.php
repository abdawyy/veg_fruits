<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class PreparationService extends Model
{
    use HasTranslations;

    /** @var list<string> */
    public array $translatable = ['name'];

    protected $fillable = [
        'code',
        'name',
        'surcharge_amount',
        'surcharge_is_percent',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'surcharge_is_percent' => 'boolean',
            'is_active' => 'boolean',
            'surcharge_amount' => 'decimal:4',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('is_enabled');
    }
}
