<?php

namespace App\Filament\Grupooliveiraneto\Resources\Entregas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Entregas\EntregaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEntrega extends ViewRecord
{
    protected static string $resource = EntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
