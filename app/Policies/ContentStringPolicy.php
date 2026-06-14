<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ContentString;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentStringPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ContentString');
    }

    public function view(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('View:ContentString');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ContentString');
    }

    public function update(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('Update:ContentString');
    }

    public function delete(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('Delete:ContentString');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ContentString');
    }

    public function restore(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('Restore:ContentString');
    }

    public function forceDelete(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('ForceDelete:ContentString');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ContentString');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ContentString');
    }

    public function replicate(AuthUser $authUser, ContentString $contentString): bool
    {
        return $authUser->can('Replicate:ContentString');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ContentString');
    }

}