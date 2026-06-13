<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProduceBox;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProduceBoxPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProduceBox');
    }

    public function view(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('View:ProduceBox');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProduceBox');
    }

    public function update(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('Update:ProduceBox');
    }

    public function delete(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('Delete:ProduceBox');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProduceBox');
    }

    public function restore(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('Restore:ProduceBox');
    }

    public function forceDelete(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('ForceDelete:ProduceBox');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProduceBox');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProduceBox');
    }

    public function replicate(AuthUser $authUser, ProduceBox $produceBox): bool
    {
        return $authUser->can('Replicate:ProduceBox');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProduceBox');
    }
}
