<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages;

use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\OcorrenciaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOcorrencia extends ViewRecord
{
    protected static string $resource = OcorrenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
