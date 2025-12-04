<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as AuthUser;

class PermissionPolicy extends BasePolicy
{
    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', Permission::class);
    }

    public function view(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'View', Permission::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', Permission::class);
    }

    public function update(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'Update', Permission::class);
    }

    public function delete(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'Delete', Permission::class);
    }

    public function restore(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'Restore', Permission::class);
    }

    public function forceDelete(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', Permission::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', Permission::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', Permission::class);
    }

    public function replicate(AuthUser $authUser, Permission $role): bool
    {
        return $this->hasPermission($authUser, 'Replicate', Permission::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', Permission::class);
    }


}
