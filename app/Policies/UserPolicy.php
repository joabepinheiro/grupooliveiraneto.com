<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', User::class);
    }

    public function view(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'View', User::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', User::class);
    }

    public function update(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Update', User::class);
    }

    public function delete(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Delete', User::class);
    }

    public function restore(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Restore', User::class);
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', User::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', User::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', User::class);
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Replicate', User::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', User::class);
    }

}
