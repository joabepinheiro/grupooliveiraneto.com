<?php

namespace App\Filament\Movelveiculos\Resources\Users;

use App\Filament\Movelveiculos\Resources\Users\Pages\CreateUser;
use App\Filament\Movelveiculos\Resources\Users\Pages\EditUser;
use App\Filament\Movelveiculos\Resources\Users\Pages\ListUsers;
use App\Filament\Movelveiculos\Resources\Users\Schemas\UserForm;
use App\Filament\Movelveiculos\Resources\Users\Tables\UsersTable;
use App\Models\User;
use App\Traits\HasResourcePermissions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = User::class;

    protected static string|null|BackedEnum $navigationIcon = 'fas-user';

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $pluralModelLabel = 'usuários';

    protected static ?string $navigationLabel = 'Usuários';

    protected static string|null|\UnitEnum $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema)->columns(12);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
