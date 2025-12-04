<?php

declare(strict_types=1);

namespace App\Policies\Entrega;

use App\Policies\BasePolicy;
use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Entrega\Entrega;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntregaPolicy extends BasePolicy
{
    use HandlesAuthorization;


    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', Entrega::class);
    }

    public function view(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'View', Entrega::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', Entrega::class);
    }

    public function update(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'Update', Entrega::class);
    }

    public function delete(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'Delete', Entrega::class);
    }

    public function restore(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'Restore', Entrega::class);
    }

    public function forceDelete(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', Entrega::class);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', Entrega::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', Entrega::class);
    }

    public function replicate(AuthUser $authUser, Entrega $entrega): bool
    {
        return $this->hasPermission($authUser, 'Replicate', Entrega::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', Entrega::class);
    }

}
