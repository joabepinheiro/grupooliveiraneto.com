<?php

namespace App\Filament\Grupooliveiraneto\Resources\Modelos\Pages;

use App\Filament\Movelveiculos\Resources\Modelos\ModeloResource;
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
