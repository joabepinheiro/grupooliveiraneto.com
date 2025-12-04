<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ActivityLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', ActivityLog::class);
    }

    public function view(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'View', ActivityLog::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', ActivityLog::class);
    }

    public function update(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'Update', ActivityLog::class);
    }

    public function delete(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'Delete', ActivityLog::class);
    }

    public function restore(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'Restore', ActivityLog::class);
    }

    public function forceDelete(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', ActivityLog::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', ActivityLog::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', ActivityLog::class);
    }

    public function replicate(AuthUser $authUser, ActivityLog $activityLog): bool
    {
        return $this->hasPermission($authUser, 'Replicate', ActivityLog::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', ActivityLog::class);
    }

}
