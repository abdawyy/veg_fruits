<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PreparationService;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreparationServicePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PreparationService');
    }

    public function view(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('View:PreparationService');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PreparationService');
    }

    public function update(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('Update:PreparationService');
    }

    public function delete(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('Delete:PreparationService');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PreparationService');
    }

    public function restore(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('Restore:PreparationService');
    }

    public function forceDelete(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('ForceDelete:PreparationService');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PreparationService');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PreparationService');
    }

    public function replicate(AuthUser $authUser, PreparationService $preparationService): bool
    {
        return $authUser->can('Replicate:PreparationService');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PreparationService');
    }

}