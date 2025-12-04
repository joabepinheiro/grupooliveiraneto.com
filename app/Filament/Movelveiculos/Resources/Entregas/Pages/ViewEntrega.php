<?php

namespace App\Filament\Movelveiculos\Resources\Entregas\Pages;

use App\Filament\Movelveiculos\Resources\Entregas\EntregaResource;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEntrega extends ViewRecord
{
    protected static string $resource = EntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),

            Action::make('assinar')
                ->label('Assinar')
                ->url(Assinatura::getUrl(['record' => $this->record]), true),
        ];
    }

}
