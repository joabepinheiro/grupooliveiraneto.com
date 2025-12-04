<?php

namespace App\Filament\Bydconquista\Resources\Entregas;

use App\Filament\Bydconquista\Resources\Entregas\Pages\CreateEntrega;
use App\Filament\Bydconquista\Resources\Entregas\Pages\EditEntrega;
use App\Filament\Bydconquista\Resources\Entregas\Pages\ListEntregas;
use App\Filament\Bydconquista\Resources\Entregas\Pages\ViewEntrega;
use App\Filament\Bydconquista\Resources\Entregas\Schemas\EntregaForm;
use App\Filament\Bydconquista\Resources\Entregas\Schemas\EntregaInfolist;
use App\Filament\Bydconquista\Resources\Entregas\Tables\EntregasTable;
use App\Models\Entrega\Entrega;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
        return EntregaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EntregaInfolist::configure($schema);
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
}
