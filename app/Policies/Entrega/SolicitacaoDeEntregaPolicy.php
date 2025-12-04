<?php

declare(strict_types=1);

namespace App\Policies\Entrega;

use App\Policies\BasePolicy;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Entrega\SolicitacaoDeEntrega;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitacaoDeEntregaPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ViewAny', SolicitacaoDeEntrega::class);
    }

    public function view(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'View', SolicitacaoDeEntrega::class);
    }

    public function create(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Create', SolicitacaoDeEntrega::class);
    }

    public function update(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'Update', SolicitacaoDeEntrega::class);
    }

    public function delete(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'Delete', SolicitacaoDeEntrega::class);
    }

    public function restore(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'Restore', SolicitacaoDeEntrega::class);
    }

    public function forceDelete(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'ForceDelete', SolicitacaoDeEntrega::class);
    }
    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'ForceDeleteAny', SolicitacaoDeEntrega::class);
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'RestoreAny', SolicitacaoDeEntrega::class);
    }

    public function replicate(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $this->hasPermission($authUser, 'Replicate', SolicitacaoDeEntrega::class);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $this->hasPermission($authUser, 'Reorder', SolicitacaoDeEntrega::class);
    }

}
