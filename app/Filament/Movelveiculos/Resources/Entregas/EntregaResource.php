<?php

namespace App\Filament\Movelveiculos\Resources\Entregas;

use App\Filament\Movelveiculos\Resources\Entregas\Pages\Assinatura;
use App\Filament\Movelveiculos\Resources\Entregas\Pages\CreateEntrega;
use App\Filament\Movelveiculos\Resources\Entregas\Pages\EditEntrega;
use App\Filament\Movelveiculos\Resources\Entregas\Pages\ListEntregas;
use App\Filament\Movelveiculos\Resources\Entregas\Pages\ViewEntrega;
use App\Filament\Movelveiculos\Resources\Entregas\Schemas\EntregaForm;
use App\Filament\Movelveiculos\Resources\Entregas\Schemas\EntregaInfolist;
use App\Filament\Movelveiculos\Resources\Entregas\Tables\EntregasTable;
use App\Models\Entrega\Entrega;
use App\Traits\HasResourcePermissions;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntregaResource extends Resource
{

    protected static ?string $model = Entrega::class;

    protected static string|null|BackedEnum $navigationIcon = 'fas-box';

    protected static ?int $navigationSort = 10;


    public static function form(Schema $schema): Schema
    {
        return EntregaForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EntregaInfolist::configure($schema)->columns(1);
    }

    public static function table(Table $table): Table
    {
        return EntregasTable::configure($table);
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
            'index' => ListEntregas::route('/'),
            'create' => CreateEntrega::route('/create'),
            'view' => ViewEntrega::route('/{record}'),
            'assinatura' => Assinatura::route('/assinaturas/{record}'),
            'edit' => EditEntrega::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'Em andamento')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }





}
