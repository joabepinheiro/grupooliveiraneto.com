<?php

namespace App\Services\Tarefa;

use App\Models\Tarefa\Tarefa;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

class RecorrenciaService
{
    public function gerarOcorrenciasParaTarefa(Tarefa $tarefa): void
    {
        if (empty($tarefa->rrule)) {
            $this->gerarOcorrenciaUnica($tarefa);
            return;
        }

        $this->gerarOcorrenciasRecorrentes($tarefa);
    }

    private function gerarOcorrenciaUnica(Tarefa $tarefa): void
    {
        // Remover todas as ocorrências normais
        $tarefa->ocorrencias()
            ->where('is_excecao', false)
            ->forceDelete();

        // Criar ocorrência única
        $tarefa->ocorrencias()->create([
            'tarefa_id'    => $tarefa->id,
            'titulo'       => $tarefa->titulo,
            'descricao'    => $tarefa->descricao,
            'data_inicio'  => $tarefa->data_inicio,
            'data_fim'     => $tarefa->data_fim,
            'status'       => $tarefa->status,
            'responsaveis' => implode($tarefa->responsaveis ?? []),
            'is_excecao'   => false,

            'created_by'   => $tarefa->created_by,
            'updated_by'   => $tarefa->updated_by,
            'deleted_by'   => $tarefa->deleted_by,
        ]);
    }

    private function gerarOcorrenciasRecorrentes(Tarefa $tarefa): void
    {
        // 1) Apaga todas as ocorrências normais
        $tarefa->ocorrencias()
            ->where('is_excecao', false)
            ->forceDelete();

        // 2) Carrega exceções (NOVA query builder)
        $excecoes = $tarefa->ocorrencias()
            ->where('is_excecao', true)
            ->select(['id', 'data_inicio'])
            ->get()
            ->keyBy(fn ($e) => $e->data_inicio->format('Y-m-d'));

        // 3) Instâncias da RRULE
        $rule = new Rule($tarefa->rrule, $tarefa->data_inicio->toDateTimeString());
        $instancias = (new ArrayTransformer())->transform($rule);

        // 4) Duração
        $duracao = $tarefa->data_inicio->diff($tarefa->data_fim);

        $novasOcorrencias = [];
        $agora = now();

        foreach ($instancias as $instancia) {

            $inicio = $instancia->getStart();
            $dataKey = $inicio->format('Y-m-d');

            // Ignora se há exceção
            if ($excecoes->has($dataKey)) {
                continue;
            }

            $novasOcorrencias[] = [
                'tarefa_id'    => $tarefa->id,
                'titulo'       => $tarefa->titulo,
                'descricao'    => $tarefa->descricao,
                'data_inicio'  => $inicio,
                'data_fim'     => (clone $inicio)->add($duracao),
                'status'       => $tarefa->status,
                'responsaveis' => implode($tarefa->responsaveis ?? []),
                'is_excecao'   => false,
                'created_at'   => $agora,
                'updated_at'   => null,

                'created_by'  => $tarefa->created_by,
                'updated_by'  => $tarefa->updated_by,
                'deleted_by'  => $tarefa->deleted_by,
            ];
        }

        // 6) Insere em lote
        if (!empty($novasOcorrencias)) {
            $tarefa->ocorrencias()->insert($novasOcorrencias);
        }
    }
}
