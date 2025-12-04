<?php

namespace App\Filament\Grupooliveiraneto\Resources\Modelos;

use App\Filament\Movelveiculos\Resources\Modelos\Pages\CreateModelo;
use App\Filament\Movelveiculos\Resources\Modelos\Pages\EditModelo;
use App\Filament\Movelveiculos\Resources\Modelos\Pages\ListModelos;
use App\Filament\Movelveiculos\Resources\Modelos\Pages\ViewModelo;
use App\Filament\Movelveiculos\Resources\Modelos\Schemas\ModeloForm;
use App\Filament\Movelveiculos\Resources\Modelos\Schemas\ModeloInfolist;
use App\Filament\Movelveiculos\Resources\Modelos\Tables\ModelosTable;
use App\Models\Modelo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModeloResource extends Resource
{
    protected static ?string $model = Modelo::class;

    protected static string|null|BackedEnum $navigationIcon = 'fas-car-side';

    protected static ?int $navigationSort = 30;

    protected static string|null|\UnitEnum $navigationGroup = 'Configurações';

    public static function form(Schema $schema): Schema
    {
        return ModeloForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ModeloInfolist::configure($schema)->columns(1);
    }

    public static function table(Table $table): Table
    {
        return ModelosTable::configure($table);
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
            'index' => ListModelos::route('/'),
            'create' => CreateModelo::route('/create'),
            'view' => ViewModelo::route('/{record}'),
            'edit' => EditModelo::route('/{record}/edit'),
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
