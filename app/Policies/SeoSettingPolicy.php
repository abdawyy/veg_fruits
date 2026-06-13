<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SeoSetting;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SeoSettingPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SeoSetting');
    }

    public function view(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('View:SeoSetting');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SeoSetting');
    }

    public function update(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('Update:SeoSetting');
    }

    public function delete(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('Delete:SeoSetting');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SeoSetting');
    }

    public function restore(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('Restore:SeoSetting');
    }

    public function forceDelete(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('ForceDelete:SeoSetting');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SeoSetting');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SeoSetting');
    }

    public function replicate(AuthUser $authUser, SeoSetting $seoSetting): bool
    {
        return $authUser->can('Replicate:SeoSetting');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SeoSetting');
    }
}
