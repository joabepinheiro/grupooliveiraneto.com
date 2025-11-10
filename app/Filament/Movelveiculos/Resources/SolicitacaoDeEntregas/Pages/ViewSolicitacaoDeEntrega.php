<?php

namespace App\Filament\Movelveiculos\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Movelveiculos\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSolicitacaoDeEntrega extends ViewRecord
{
    protected static string $resource = SolicitacaoDeEntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
