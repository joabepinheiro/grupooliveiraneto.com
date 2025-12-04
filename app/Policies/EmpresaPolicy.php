<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Empresa;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpresaPolicy extends BasePolicy
{


    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', Empresa::class);
    }

    public function view(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'View', Empresa::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', Empresa::class);
    }

    public function update(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'Update', Empresa::class);
    }

    public function delete(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'Delete', Empresa::class);
    }

    public function restore(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'Restore', Empresa::class);
    }

    public function forceDelete(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', Empresa::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', Empresa::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', Empresa::class);
    }

    public function replicate(AuthUser $authUser, Empresa $empresa): bool
    {
        return $this->hasPermission($authUser, 'Replicate', Empresa::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', Empresa::class);
    }

}
