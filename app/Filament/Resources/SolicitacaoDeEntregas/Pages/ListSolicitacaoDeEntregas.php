<?php

namespace App\Filament\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;

use App\Models\Entrega\SolicitacaoDeEntrega;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSolicitacaoDeEntregas extends ListRecords
{
    protected static string $resource = SolicitacaoDeEntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
