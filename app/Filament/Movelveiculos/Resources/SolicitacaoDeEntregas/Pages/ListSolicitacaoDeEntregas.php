<?php

namespace App\Filament\Movelveiculos\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Movelveiculos\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
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
