<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Modelo;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModeloPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', Modelo::class);
    }

    public function view(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'View', Modelo::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', Modelo::class);
    }

    public function update(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'Update', Modelo::class);
    }

    public function delete(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'Delete', Modelo::class);
    }

    public function restore(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'Restore', Modelo::class);
    }

    public function forceDelete(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', Modelo::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', Modelo::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', Modelo::class);
    }

    public function replicate(AuthUser $authUser, Modelo $modelo): bool
    {
        return $this->hasPermission($authUser, 'Replicate', Modelo::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', Modelo::class);
    }

}
