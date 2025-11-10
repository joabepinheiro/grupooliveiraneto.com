<?php

namespace App\Filament\Grupooliveiraneto\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Grupooliveiraneto\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
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
