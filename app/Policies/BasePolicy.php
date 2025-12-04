<?php

namespace App\Policies;

use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User;

abstract class BasePolicy
{
    protected function panel(): string
    {
        return Filament::getCurrentPanel()?->getId() ?? 'app';
    }

    protected function hasPermission(User $user, string $action, string $model): bool
    {

        $permission = "{$this->panel()}::{$model}.{$action}";

        return $user->can($permission);
    }
}
