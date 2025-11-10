<?php

namespace App\Filament\Resources\Modelos\Pages;

use App\Filament\Resources\Modelos\ModeloResource;
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
