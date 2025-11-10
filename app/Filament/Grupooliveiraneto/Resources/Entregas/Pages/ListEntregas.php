<?php

namespace App\Filament\Grupooliveiraneto\Resources\Entregas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Entregas\EntregaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEntregas extends ListRecords
{
    protected static string $resource = EntregaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
