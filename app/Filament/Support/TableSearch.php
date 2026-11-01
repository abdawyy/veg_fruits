<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Case-insensitive search for admin tables.
 * Translated names are JSON, and MySQL compares JSON with a binary collation,
 * so Filament's default LIKE misses the names shown in the table.
 */
final class TableSearch
{
    /**
     * @param  list<string>  $columns  Attribute, or relation path ending in an attribute (items.product.name).
     */
    public static function apply(Builder $query, string $search, array $columns): Builder
    {
        $words = self::words($search);

        if ($words === [] || $columns === []) {
            return $query;
        }

        $query->where(function (Builder $query) use ($words, $columns): void {
            foreach ($words as $word) {
                $like = '%'.addcslashes(mb_strtolower($word), '%_\\').'%';

                $query->where(function (Builder $query) use ($columns, $like): void {
                    foreach ($columns as $column) {
                        self::orWhereColumn($query, $column, $like);
                    }
                });
            }
        });

        return $query;
    }

    /**
     * @return list<string>
     */
    private static function words(string $search): array
    {
        $parts = preg_split('/\s+/u', trim($search)) ?: [];

        return array_values(array_filter(
            $parts,
            fn (string $word): bool => $word !== '',
        ));
    }

    private static function orWhereColumn(Builder $query, string $column, string $like): void
    {
        $parts = explode('.', $column);

        foreach ($parts as $part) {
            if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $part)) {
                return;
            }
        }

        $model = $query->getModel();
        $relations = [];

        foreach (array_slice($parts, 0, -1) as $part) {
            if (! $model->isRelation($part)) {
                return;
            }

            $relations[] = $part;
            $model = $model->{$part}()->getRelated();
        }

        $attribute = $parts[array_key_last($parts)];

        if ($relations !== []) {
            $query->orWhereHas(implode('.', $relations), function (Builder $query) use ($attribute, $like): void {
                $query->where(function (Builder $query) use ($attribute, $like): void {
                    self::orWhereAttribute($query, $attribute, $like);
                });
            });

            return;
        }

        self::orWhereAttribute($query, $attribute, $like);
    }

    private static function orWhereAttribute(Builder $query, string $attribute, string $like): void
    {
        $model = $query->getModel();
        $wrapped = $query->getQuery()->getGrammar()->wrap($model->getTable().'.'.$attribute);
        $driver = $query->getConnection()->getDriverName();

        if (self::isJsonAttribute($model, $attribute)) {
            foreach (['en', 'ar'] as $locale) {
                $sql = match ($driver) {
                    'mysql', 'mariadb' => "LOWER(COALESCE(JSON_UNQUOTE(JSON_EXTRACT({$wrapped}, '$.{$locale}')), '')) LIKE ?",
                    'pgsql' => "LOWER(COALESCE({$wrapped}->>'{$locale}', '')) LIKE ?",
                    default => "LOWER(COALESCE(json_extract({$wrapped}, '$.{$locale}'), '')) LIKE ?",
                };

                $query->orWhereRaw($sql, [$like]);
            }

            return;
        }

        $query->orWhereRaw("LOWER({$wrapped}) LIKE ?", [$like]);
    }

    private static function isJsonAttribute(Model $model, string $attribute): bool
    {
        if (property_exists($model, 'translatable') && in_array($attribute, $model->translatable, true)) {
            return true;
        }

        $cast = $model->getCasts()[$attribute] ?? null;

        if (! is_string($cast)) {
            return false;
        }

        return str_starts_with($cast, 'array')
            || str_starts_with($cast, 'json')
            || in_array($cast, ['object', 'collection', 'encrypted:array', 'encrypted:json'], true);
    }
}
