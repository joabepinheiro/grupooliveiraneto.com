<?php

namespace App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas;

use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Pages\CreateSolicitacaoDeEntrega;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Pages\EditSolicitacaoDeEntrega;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Pages\ListSolicitacaoDeEntregas;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Pages\ViewSolicitacaoDeEntrega;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Schemas\SolicitacaoDeEntregaForm;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Schemas\SolicitacaoDeEntregaInfolist;
use App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Tables\SolicitacaoDeEntregasTable;
use App\Models\Empresa;
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

    protected static string|BackedEnum|null $navigationIcon = 'fas-share-from-square';

    protected static ?string $modelLabel = 'Solicitação de entrega';

    protected static ?string $pluralModelLabel = 'Solicitações de entrega';

    protected static ?string $navigationLabel = 'Solicitações de entrega';


    public static function form(Schema $schema): Schema
    {
        return SolicitacaoDeEntregaForm::configure($schema)->columns(1);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SolicitacaoDeEntregaInfolist::configure($schema)->columns(1);
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'Solicitada')
            ->where('empresa_id', '=', Empresa::BYD_CONQUISTA_ID)
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
