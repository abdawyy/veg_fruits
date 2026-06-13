<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SiteVisitor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SiteVisitorPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SiteVisitor');
    }

    public function view(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('View:SiteVisitor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SiteVisitor');
    }

    public function update(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('Update:SiteVisitor');
    }

    public function delete(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('Delete:SiteVisitor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SiteVisitor');
    }

    public function restore(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('Restore:SiteVisitor');
    }

    public function forceDelete(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('ForceDelete:SiteVisitor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SiteVisitor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SiteVisitor');
    }

    public function replicate(AuthUser $authUser, SiteVisitor $siteVisitor): bool
    {
        return $authUser->can('Replicate:SiteVisitor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SiteVisitor');
    }
}
