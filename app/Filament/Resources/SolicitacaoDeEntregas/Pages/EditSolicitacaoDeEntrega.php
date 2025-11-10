<?php

namespace App\Filament\Resources\SolicitacaoDeEntregas\Pages;

use App\Filament\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSolicitacaoDeEntrega extends EditRecord
{
    protected static string $resource = SolicitacaoDeEntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
