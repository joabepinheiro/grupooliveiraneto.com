<?php

namespace App\Models\Tarefa;

use App\Models\AbstractModel;
use App\Models\Entrega;
use App\Models\User;

use App\Services\Tarefa\RecorrenciaService;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Recurr\Frequency;
use Recurr\Rule;

class Tarefa extends AbstractModel
{

    use SoftDeletes;
    use LogsAllActivity;


    protected $table = 'agenda_tarefas';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'rrule',
        'status',
        'responsaveis',

        'recorrencia_tem',
        'recorrencia_intervalo',
        'recorrencia_frequencia',
        'recorrencia_dias_semana',
        'recorrencia_tipo_fim',
        'recorrencia_data_fim',
        'recorrencia_quantidade_ocorrencias',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $casts = [
        'recorrencia_dias_semana' => 'array',
        'responsaveis' => 'array',

        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',

        'recorrencia_data_fim' => 'datetime',
    ];


    protected static function booted(): void
    {
        static::created(function (Tarefa $tarefa) {
            app(RecorrenciaService::class)->gerarOcorrenciasParaTarefa($tarefa);
        });

        static::updated(function (Tarefa $tarefa) {
            app(RecorrenciaService::class)->gerarOcorrenciasParaTarefa($tarefa);
        });

        static::saving(function (Tarefa $tarefa) {
            $tarefa->rrule = $tarefa->buildRRule();
        });
    }


    public function ocorrencias(): HasMany
    {
        return $this->hasMany(Ocorrencia::class, 'tarefa_id');
    }

    /**
     * Retornar a duração padrão da tarefa (para gerar ocorrências)
     */
    public function getDuracao()
    {
        return $this->data_inicio->diff($this->data_fim);
    }

    public function buildRRule(): ?string
    {
        if (!$this->recorrencia_tem) {
            return null;
        }

        $rule = new Rule();

        /*
        |--------------------------------------------------------------------------
        | Frequência (Daily, Weekly, Monthly, Yearly)
        |--------------------------------------------------------------------------
        */
        $rule->setFreq(match ($this->recorrencia_frequencia) {
            'DAILY'   => Frequency::DAILY,
            'WEEKLY'  => Frequency::WEEKLY,
            'MONTHLY' => Frequency::MONTHLY,
            'YEARLY'  => Frequency::YEARLY,
            default   => Frequency::DAILY,
        });

        /*
        |--------------------------------------------------------------------------
        | Intervalo
        |--------------------------------------------------------------------------
        */
        if ($this->recorrencia_intervalo) {
            $rule->setInterval((int) $this->recorrencia_intervalo);
        }

        /*
        |--------------------------------------------------------------------------
        | DTSTART
        |--------------------------------------------------------------------------
        */
        if ($this->data_inicio) {
            $rule->setStartDate($this->data_inicio);
        }

        /*
        |--------------------------------------------------------------------------
        | BYDAY — Somente se semanal
        |--------------------------------------------------------------------------
        */
        if (
            $this->recorrencia_frequencia === 'WEEKLY'
            && is_array($this->recorrencia_dias_semana)
            && count($this->recorrencia_dias_semana) > 0
        ) {
            $rule->setByDay($this->recorrencia_dias_semana);
        }

        /*
        |--------------------------------------------------------------------------
        | Término da recorrência
        |--------------------------------------------------------------------------
        |
        | 'nunca' → sem UNTIL nem COUNT
        | 'em'    → UNTIL
        | 'apos'  → COUNT
        |--------------------------------------------------------------------------
        */

        if ($this->recorrencia_tipo_fim === 'em' && $this->recorrencia_data_fim) {

            $rule->setUntil($this->recorrencia_data_fim);

        } elseif ($this->recorrencia_tipo_fim === 'apos' && $this->recorrencia_quantidade_ocorrencias) {

            $rule->setCount((int) $this->recorrencia_quantidade_ocorrencias);
        }

        return $rule->getString();
    }

}
