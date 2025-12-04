<?php

namespace App\Filament\Movelveiculos\Resources\Entregas\Pages;

use App\Filament\Movelveiculos\Resources\Entregas\EntregaResource;
use App\Filament\Resources\EntregaResource\Pages\Assinatura;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Actions;

class EditEntrega extends EditRecord
{
    protected static string $resource = EntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            Action::make('assinar')
                ->label('Assinar')
                ->url(\App\Filament\Movelveiculos\Resources\Entregas\Pages\Assinatura::getUrl(['record' => $this->record]), true),
        ];
    }
}
