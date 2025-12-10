<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\TarefaResource;
use App\Services\Tarefa\RecorrenciaService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTarefa extends EditRecord
{
    protected static string $resource = TarefaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
