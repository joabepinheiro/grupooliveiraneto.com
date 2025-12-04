<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\TarefaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTarefa extends ViewRecord
{
    protected static string $resource = TarefaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
