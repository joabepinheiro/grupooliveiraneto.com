<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias;

use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages\PainelDeTarefas;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages\CreateOcorrencia;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages\EditOcorrencia;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages\ListOcorrencias;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages\ViewOcorrencia;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\RelationManagers\LogsRelationManager;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Schemas\OcorrenciaForm;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Schemas\OcorrenciaInfolist;
use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Tables\OcorrenciasTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class OcorrenciaResource extends Resource
{
    protected static ?string $model = \App\Models\Tarefa\Ocorrencia::class;

    protected static string|BackedEnum|null $navigationIcon ='fas-calendar-check';

    protected static ?string $navigationLabel   = 'Ocorrências';
    protected static ?string $modelLabel        = 'Ocorrência';
    protected static ?string $pluralModelLabel  = 'Ocorrências';

    protected static string | UnitEnum | null $navigationGroup = 'Tarefas';


    public static function form(Schema $schema): Schema
    {
        return OcorrenciaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OcorrenciaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OcorrenciasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'logs' => LogsRelationManager::class,
        ];
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOcorrencias::route('/'),
            //'create' => CreateOcorrencia::route('/create'),
            'view' => ViewOcorrencia::route('/{record}'),
            'edit' => EditOcorrencia::route('/{record}/edit'),
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
