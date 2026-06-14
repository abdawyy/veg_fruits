<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PackagingType;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagingTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PackagingType');
    }

    public function view(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('View:PackagingType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PackagingType');
    }

    public function update(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('Update:PackagingType');
    }

    public function delete(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('Delete:PackagingType');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PackagingType');
    }

    public function restore(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('Restore:PackagingType');
    }

    public function forceDelete(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('ForceDelete:PackagingType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PackagingType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PackagingType');
    }

    public function replicate(AuthUser $authUser, PackagingType $packagingType): bool
    {
        return $authUser->can('Replicate:PackagingType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PackagingType');
    }

}