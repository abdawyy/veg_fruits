<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\HomeBanner;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeBannerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HomeBanner');
    }

    public function view(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('View:HomeBanner');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HomeBanner');
    }

    public function update(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('Update:HomeBanner');
    }

    public function delete(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('Delete:HomeBanner');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HomeBanner');
    }

    public function restore(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('Restore:HomeBanner');
    }

    public function forceDelete(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('ForceDelete:HomeBanner');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HomeBanner');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HomeBanner');
    }

    public function replicate(AuthUser $authUser, HomeBanner $homeBanner): bool
    {
        return $authUser->can('Replicate:HomeBanner');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HomeBanner');
    }

}