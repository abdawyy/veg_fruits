<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;

final class AdminRolePermissions
{
    /**
     * @param  list<string>  $subjects  Model names used in permission keys (e.g. Order, Product).
     * @return list<string>
     */
    public static function forSubjects(string ...$subjects): array
    {
        if ($subjects === []) {
            return [];
        }

        return Permission::query()
            ->where(function ($query) use ($subjects): void {
                foreach ($subjects as $subject) {
                    $query->orWhere('name', 'like', "%:{$subject}");
                }
            })
            ->pluck('name')
            ->all();
    }

    /**
     * @param  list<string>  $permissionNames
     * @return list<string>
     */
    public static function only(array $permissionNames): array
    {
        return Permission::query()
            ->whereIn('name', $permissionNames)
            ->pluck('name')
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function analyticsViews(): array
    {
        return Permission::query()
            ->where('name', 'like', 'View:%')
            ->where(function ($query): void {
                $query
                    ->where('name', 'like', '%Widget')
                    ->orWhere('name', 'like', '%Chart')
                    ->orWhereIn('name', [
                        'View:SiteVisitor',
                        'View:AdminGuide',
                    ]);
            })
            ->pluck('name')
            ->all();
    }
}
