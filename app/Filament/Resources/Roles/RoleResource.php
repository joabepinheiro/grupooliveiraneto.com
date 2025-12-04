<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Schemas\RoleInfolist;
use App\Filament\Resources\Roles\Tables\RolesTable;
use App\Models\Permission;
use App\Models\Role;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'fas-user-lock';

    protected static string|null|\UnitEnum $navigationGroup = 'Controle de acesso';

    protected static ?string $modelLabel = 'Função';

    protected static ?string $pluralModelLabel = 'Funções';

    protected static ?string $navigationLabel = 'Funções';


    protected static ?int $navigationSort = 100;


    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema)->columns(1);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            //'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'viewAny';
        return auth()->user()->can($permission);
    }

    public static function canView(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'view';
        return auth()->user()->can($permission);
    }

    public static function canCreate(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'create';
        return auth()->user()->can($permission);
    }

    public static function canEdit(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'update';
        return auth()->user()->can($permission);
    }

    public static function canDelete(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'delete';
        return auth()->user()->can($permission);
    }

    public static function canRestore(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'restore';
        return auth()->user()->can($permission);
    }

    public static function canForceDelete(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'forceDelete';
        return auth()->user()->can($permission);
    }

    public static function canForceDeleteAny(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'forceDeleteAny';
        return auth()->user()->can($permission);
    }

    public static function canRestoreAny(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'restoreAny';
        return auth()->user()->can($permission);
    }

    public static function canReplicate(Model $record): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'replicate';
        return auth()->user()->can($permission);
    }

    public static function canReorder(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'reorder';
        return auth()->user()->can($permission);
    }

    public static function canDeleteAny(): bool
    {
        $permission = Filament::getCurrentPanel()->getId() .'::'.self::class.'::'.'deleteAny';
        return auth()->user()->can($permission);
    }
}
