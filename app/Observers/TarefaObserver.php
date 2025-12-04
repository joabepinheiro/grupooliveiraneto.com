<?php

namespace App\Observers;

use App\Models\Tarefa\Tarefa;
use App\Services\Tarefa\RecorrenciaService;

class TarefaObserver
{
    /**
     * Handle the Tarefa "created" event.
     */
    public function created(Tarefa $tarefa): void
    {
        app(RecorrenciaService::class)
            ->gerarOcorrenciasParaTarefa($tarefa);
    }

    /**
     * Handle the Tarefa "updated" event.
     */
    public function updated(Tarefa $tarefa): void
    {
        app(\App\Services\Tarefa\RecorrenciaService::class)
            ->gerarOcorrenciasParaTarefa($tarefa);
    }

    /**
     * Handle the Tarefa "deleted" event.
     */
    public function deleted(Tarefa $tarefa): void
    {
        //
    }

    /**
     * Handle the Tarefa "restored" event.
     */
    public function restored(Tarefa $tarefa): void
    {
        //
    }

    /**
     * Handle the Tarefa "force deleted" event.
     */
    public function forceDeleted(Tarefa $tarefa): void
    {
        //
    }
}
