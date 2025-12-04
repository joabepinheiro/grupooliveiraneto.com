<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Role;
use Illuminate\Foundation\Auth\User as AuthUser;

use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', Role::class);
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'View', Role::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', Role::class);
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'Update', Role::class);
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'Delete', Role::class);
    }

    public function restore(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'Restore', Role::class);
    }

    public function forceDelete(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', Role::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', Role::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', Role::class);
    }

    public function replicate(AuthUser $authUser, Role $role): bool
    {
        return $this->hasPermission($authUser, 'Replicate', Role::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', Role::class);
    }

}
