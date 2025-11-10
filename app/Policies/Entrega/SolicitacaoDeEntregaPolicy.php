<?php

declare(strict_types=1);

namespace App\Policies\Entrega;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Entrega\SolicitacaoDeEntrega;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitacaoDeEntregaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SolicitacaoDeEntrega');
    }

    public function view(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('View:SolicitacaoDeEntrega');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SolicitacaoDeEntrega');
    }

    public function update(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('Update:SolicitacaoDeEntrega');
    }

    public function delete(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('Delete:SolicitacaoDeEntrega');
    }

    public function restore(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('Restore:SolicitacaoDeEntrega');
    }

    public function forceDelete(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('ForceDelete:SolicitacaoDeEntrega');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SolicitacaoDeEntrega');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SolicitacaoDeEntrega');
    }

    public function replicate(AuthUser $authUser, SolicitacaoDeEntrega $solicitacaoDeEntrega): bool
    {
        return $authUser->can('Replicate:SolicitacaoDeEntrega');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SolicitacaoDeEntrega');
    }

}