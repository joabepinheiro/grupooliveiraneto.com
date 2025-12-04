<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages\CreateTarefa;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages\EditTarefa;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages\ListTarefas;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages\ViewTarefa;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaInfolist;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Tables\TarefasTable;
use App\Models\Tarefa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TarefaResource extends Resource
{
    protected static ?string $model = Tarefa\Tarefa::class;

    protected static string|BackedEnum|null $navigationIcon ='fas-list-check';

    public static function form(Schema $schema): Schema
    {
        return TarefaForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TarefaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TarefasTable::configure($table);
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
            'index' => ListTarefas::route('/'),
            'create' => CreateTarefa::route('/create'),
            'view' => ViewTarefa::route('/{record}'),
            'edit' => EditTarefa::route('/{record}/edit'),
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
