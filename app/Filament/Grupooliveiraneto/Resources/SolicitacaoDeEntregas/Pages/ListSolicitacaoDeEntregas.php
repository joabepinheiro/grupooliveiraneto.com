<?php

namespace App\Filament\Grupooliveiraneto\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Grupooliveiraneto\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

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
