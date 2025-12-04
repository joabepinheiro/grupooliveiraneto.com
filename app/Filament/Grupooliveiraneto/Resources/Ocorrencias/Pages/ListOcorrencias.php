<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages;

use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\OcorrenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOcorrencias extends ListRecords
{
    protected static string $resource = OcorrenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
