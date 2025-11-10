<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Modelo;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModeloPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Modelo');
    }

    public function view(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('View:Modelo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Modelo');
    }

    public function update(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('Update:Modelo');
    }

    public function delete(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('Delete:Modelo');
    }

    public function restore(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('Restore:Modelo');
    }

    public function forceDelete(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('ForceDelete:Modelo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Modelo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Modelo');
    }

    public function replicate(AuthUser $authUser, Modelo $modelo): bool
    {
        return $authUser->can('Replicate:Modelo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Modelo');
    }

}