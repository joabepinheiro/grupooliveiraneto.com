<?php

namespace App\Filament\Bydconquista\Resources\Modelos\Pages;

use App\Filament\Bydconquista\Resources\Modelos\ModeloResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewModelo extends ViewRecord
{
    protected static string $resource = ModeloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
