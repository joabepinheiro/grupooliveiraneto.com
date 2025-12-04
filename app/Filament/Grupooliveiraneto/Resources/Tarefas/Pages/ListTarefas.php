<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\TarefaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTarefas extends ListRecords
{
    protected static string $resource = TarefaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
