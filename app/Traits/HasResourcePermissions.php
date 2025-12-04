<?php

namespace App\Traits;

use Filament\Facades\Filament;

trait HasResourcePermissions
{
    protected static function hasPermission(string $action): bool
    {
        $panel = Filament::getCurrentPanel()->getId();
        $permission = "{$panel}::" . static::class . "::{$action}";

        return auth()->user()?->can($permission) ?? false;
    }

    public static function canViewAny(): bool
    {
        return static::hasPermission('viewAny');
    }

    public static function canView($record): bool
    {
        return static::hasPermission('view');
    }

    public static function canCreate(): bool
    {

        return static::hasPermission('create');
    }

    public static function canEdit($record): bool
    {
        return static::hasPermission('update');
    }

    public static function canDelete($record): bool
    {
        return static::hasPermission('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::hasPermission('deleteAny');
    }

    public static function canRestore($record): bool
    {
        return static::hasPermission('restore');
    }

    public static function canRestoreAny(): bool
    {
        return static::hasPermission('restoreAny');
    }

    public static function canForceDelete($record): bool
    {
        return static::hasPermission('forceDelete');
    }

    public static function canForceDeleteAny(): bool
    {
        return static::hasPermission('forceDeleteAny');
    }

    public static function canReplicate($record): bool
    {
        return static::hasPermission('replicate');
    }

    public static function canReorder(): bool
    {
        return static::hasPermission('reorder');
    }
}
