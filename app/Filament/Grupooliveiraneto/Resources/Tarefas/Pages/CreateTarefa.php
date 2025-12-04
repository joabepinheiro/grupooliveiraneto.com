<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

use App\Filament\Grupooliveiraneto\Resources\Tarefas\TarefaResource;
use App\Services\Tarefa\RecorrenciaService;
use Filament\Resources\Pages\CreateRecord;

class CreateTarefa extends CreateRecord
{
    protected static string $resource = TarefaResource::class;




    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Se não é recorrente, limpa a RRULE
        |--------------------------------------------------------------------------
        */
        if (empty($data['tem_recorrencia'])) {
            $data['rrule'] = null;

            unset(
                $data['frequencia'],
                $data['intervalo'],
                $data['dias_semana'],
                $data['rrule_manual'],
                $data['tipo_fim'],
                $data['data_fim_recorrencia'],
                $data['quantidade_ocorrencias']
            );

            return $data;
        }


        /*
        |--------------------------------------------------------------------------
        | Monta RRULE personalizada (CUSTOM)
        |--------------------------------------------------------------------------
        */
        if ($data['frequencia'] === 'CUSTOM') {
            $data['rrule'] = $data['rrule_manual'];

        } else {

            /*
            |--------------------------------------------------------------------------
            | Montagem padrão de RRULE (DAILY, WEEKLY, MONTHLY, YEARLY)
            |--------------------------------------------------------------------------
            */
            $rrule = "FREQ={$data['frequencia']};INTERVAL=" . ($data['intervalo'] ?? 1);

            // BYDAY (apenas semanal)
            if ($data['frequencia'] === 'WEEKLY' && !empty($data['dias_semana'])) {
                $rrule .= ";BYDAY=" . implode(',', $data['dias_semana']);
            }

            /*
            |--------------------------------------------------------------------------
            | Término da recorrência (tipo_fim)
            |--------------------------------------------------------------------------
            */
            if (!empty($data['tipo_fim'])) {
                switch ($data['tipo_fim']) {

                    case 'em': // UNTIL
                        if (!empty($data['data_fim_recorrencia'])) {
                            $until = \Carbon\Carbon::parse($data['data_fim_recorrencia'])->format('Ymd\T000000\Z');
                            $rrule .= ";UNTIL={$until}";
                        }
                        break;

                    case 'apos': // COUNT
                        if (!empty($data['quantidade_ocorrencias'])) {
                            $rrule .= ";COUNT=" . intval($data['quantidade_ocorrencias']);
                        }
                        break;

                    case 'nunca':
                    default:
                        // nada a adicionar
                        break;
                }
            }

            $data['rrule'] = $rrule;
        }


        /*
        |--------------------------------------------------------------------------
        | Remove campos auxiliares
        |--------------------------------------------------------------------------
        */
        unset(
            $data['tem_recorrencia'],
            $data['frequencia'],
            $data['intervalo'],
            $data['dias_semana'],
            $data['rrule_manual'],
            $data['tipo_fim'],
            $data['data_fim_recorrencia'],
            $data['quantidade_ocorrencias']
        );

        return $data;
    }


}
