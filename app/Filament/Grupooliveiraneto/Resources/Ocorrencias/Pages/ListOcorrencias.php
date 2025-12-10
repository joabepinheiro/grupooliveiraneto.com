<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Pages;

use App\Filament\Grupooliveiraneto\Resources\Ocorrencias\OcorrenciaResource;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Models\Tarefa\Tarefa;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOcorrencias extends ListRecords
{
    protected static string $resource = OcorrenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [


            Action::make('criar-tarefa')
                ->modalHeading('Criar tarefa')
                ->modal()
                ->label('Criar tarefa')
                ->schema(TarefaForm::components())
                ->action(function (array $data): Tarefa {
                    return Tarefa::create($data);
                })
        ];
    }
}
