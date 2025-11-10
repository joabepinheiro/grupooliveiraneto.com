<?php

namespace App\Filament\Resources\SolicitacaoDeEntregas;

use App\Filament\Resources\SolicitacaoDeEntregas\Pages\CreateSolicitacaoDeEntrega;
use App\Filament\Resources\SolicitacaoDeEntregas\Pages\EditSolicitacaoDeEntrega;
use App\Filament\Resources\SolicitacaoDeEntregas\Pages\ListSolicitacaoDeEntregas;
use App\Filament\Resources\SolicitacaoDeEntregas\Pages\ViewSolicitacaoDeEntrega;
use App\Filament\Resources\SolicitacaoDeEntregas\Schemas\SolicitacaoDeEntregaForm;
use App\Filament\Resources\SolicitacaoDeEntregas\Schemas\SolicitacaoDeEntregaInfolist;
use App\Filament\Resources\SolicitacaoDeEntregas\Tables\SolicitacaoDeEntregasTable;
use App\Models\Entrega\SolicitacaoDeEntrega;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SolicitacaoDeEntregaResource extends Resource
{
    protected static ?string $model = SolicitacaoDeEntrega::class;

    protected static string|null|BackedEnum $navigationIcon = 'fas-share-from-square';

    protected static ?string $modelLabel = 'Solicitação de entrega';

    protected static ?string $pluralModelLabel = 'Solicitações de entrega';

    protected static ?string $navigationLabel = 'Solicitações de entrega';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SolicitacaoDeEntregaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SolicitacaoDeEntregaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SolicitacaoDeEntregasTable::configure($table);
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
            'index' => ListSolicitacaoDeEntregas::route('/'),
            'create' => CreateSolicitacaoDeEntrega::route('/create'),
            'view' => ViewSolicitacaoDeEntrega::route('/{record}'),
            'edit' => EditSolicitacaoDeEntrega::route('/{record}/edit'),
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
