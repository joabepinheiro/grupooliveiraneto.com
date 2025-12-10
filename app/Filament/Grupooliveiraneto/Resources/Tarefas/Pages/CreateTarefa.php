<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\TarefaResource;
use App\Services\Tarefa\RecorrenciaService;
use Filament\Resources\Pages\CreateRecord;

class CreateTarefa extends CreateRecord
{
    protected static string $resource = TarefaResource::class;
}
