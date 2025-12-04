<?php

namespace App\Filament\Bydconquista\Resources\Modelos\Pages;

use App\Filament\Bydconquista\Resources\Modelos\ModeloResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModelos extends ListRecords
{
    protected static string $resource = ModeloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
