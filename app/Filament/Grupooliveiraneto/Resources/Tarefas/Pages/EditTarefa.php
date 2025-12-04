<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Pages;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Não recorrente → limpa RRULE e remove campos auxiliares
        |--------------------------------------------------------------------------
        */
        if (empty($data['tem_recorrencia'])) {
            $data['rrule'] = null;

            unset(
                $data['tem_recorrencia'],
                $data['frequencia'],
                $data['intervalo'],
                $data['dias_semana'],
                $data['rrule_manual'],
                $data['termina_em'],
                $data['termina_em_data'],
                $data['termina_apos'],
            );

            return $data;
        }


        /*
        |--------------------------------------------------------------------------
        | CUSTOM RRULE manual
        |--------------------------------------------------------------------------
        */
        if (($data['frequencia'] ?? null) === 'CUSTOM') {

            $data['rrule'] = $data['rrule_manual'] ?? null;

            unset(
                $data['tem_recorrencia'],
                $data['frequencia'],
                $data['intervalo'],
                $data['dias_semana'],
                $data['termina_em'],
                $data['termina_em_data'],
                $data['termina_apos'],
                $data['rrule_manual'],
            );

            return $data;
        }


        /*
        |--------------------------------------------------------------------------
        | Construção RRULE padrão (DAILY, WEEKLY, MONTHLY, YEARLY)
        |--------------------------------------------------------------------------
        */
        $frequencia = $data['frequencia'] ?? 'DAILY';
        $intervalo = $data['intervalo'] ?? 1;

        $rruleParts = [
            "FREQ={$frequencia}"
        ];

        // Intervalo (não precisa colocar INTERVAL=1, só se for maior)
        if ($intervalo > 1) {
            $rruleParts[] = "INTERVAL={$intervalo}";
        }

        // BYDAY (apenas WEEKLY)
        if ($frequencia === 'WEEKLY' && !empty($data['dias_semana'])) {
            $rruleParts[] = "BYDAY=" . implode(',', $data['dias_semana']);
        }

        /*
        |--------------------------------------------------------------------------
        | Término da recorrência
        |--------------------------------------------------------------------------
        |  termina_em → tipo de finalização:
        |      - 'data'  → UNTIL
        |      - 'apos'  → COUNT
        |      - 'nunca' → nenhum término adicional
        |--------------------------------------------------------------------------
        */
        $tipoFim = $data['termina_em'] ?? 'nunca';

        switch ($tipoFim) {

            case 'data':
                if (!empty($data['termina_em_data'])) {
                    $until = \Carbon\Carbon::parse($data['termina_em_data'])
                        ->format('Ymd\T000000\Z');

                    $rruleParts[] = "UNTIL={$until}";
                }
                break;

            case 'apos':
                if (!empty($data['termina_apos'])) {
                    $rruleParts[] = "COUNT=" . intval($data['termina_apos']);
                }
                break;

            case 'nunca':
            default:
                // sem término
                break;
        }


        $data['rrule'] = implode(';', $rruleParts);


        /*
        |--------------------------------------------------------------------------
        | Remove campos auxiliares antes de salvar no banco
        |--------------------------------------------------------------------------
        */
        unset(
            $data['tem_recorrencia'],
            $data['frequencia'],
            $data['intervalo'],
            $data['dias_semana'],
            $data['rrule_manual'],
            $data['termina_em'],
            $data['termina_em_data'],
            $data['termina_apos'],
        );

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Se não há RRULE, só desmarca recorrência
        if (empty($data['rrule'])) {
            $data['tem_recorrencia'] = false;
            return $data;
        }

        $data['tem_recorrencia'] = true;

        // Quebra a RRULE em partes
        $rruleParts = collect(explode(';', $data['rrule']))
            ->mapWithKeys(function ($part) {
                [$key, $value] = explode('=', $part);
                return [$key => $value];
            });

        // -----------------------------
        // FREQ
        // -----------------------------
        if ($rruleParts->has('FREQ')) {
            $freq = $rruleParts->get('FREQ');

            if (in_array($freq, ['DAILY', 'WEEKLY', 'MONTHLY', 'YEARLY'])) {
                $data['frequencia'] = $freq;
            } else {
                // Não é frequência básica -> CUSTOM
                $data['frequencia'] = 'CUSTOM';
                $data['rrule_manual'] = $data['rrule'];
                return $data;
            }

        } else {
            // Sem FREQ = CUSTOM
            $data['frequencia'] = 'CUSTOM';
            $data['rrule_manual'] = $data['rrule'];
            return $data;
        }

        // -----------------------------
        // INTERVAL
        // -----------------------------
        if ($rruleParts->has('INTERVAL')) {
            $data['intervalo'] = intval($rruleParts->get('INTERVAL'));
        } else {
            $data['intervalo'] = 1;
        }

        // -----------------------------
        // Dias da semana (WEEKLY)
        // -----------------------------
        if ($data['frequencia'] === 'WEEKLY' && $rruleParts->has('BYDAY')) {
            $data['dias_semana'] = explode(',', $rruleParts->get('BYDAY'));
        } else {
            $data['dias_semana'] = [];
        }

        // -----------------------------
        // Termina em: UNTIL / COUNT
        // -----------------------------
        $data['termina_em'] = 'NUNCA';
        $data['termina_em_data'] = null;
        $data['termina_apos'] = null;

        // UNTIL (data final)
        if ($rruleParts->has('UNTIL')) {
            $carbon = \Carbon\Carbon::createFromFormat('Ymd\THis\Z', $rruleParts->get('UNTIL'));
            $data['termina_em'] = 'DATA';
            $data['termina_em_data'] = $carbon->format('Y-m-d');
        }

        // COUNT (nº de ocorrências)
        if ($rruleParts->has('COUNT')) {
            $data['termina_em'] = 'OCORRENCIAS';
            $data['termina_apos'] = intval($rruleParts->get('COUNT'));
        }

        return $data;
    }


}
