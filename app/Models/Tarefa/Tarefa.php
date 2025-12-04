<?php

namespace App\Models\Tarefa;

use App\Models\AbstractModel;
use App\Models\User;
use App\Observers\TarefaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Recurr\Rule;

#[ObservedBy([TarefaObserver::class])]
class Tarefa extends AbstractModel
{
    protected $table = 'agenda_tarefas';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'rrule',
        'status',
        'responsaveis',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'tem_recorrencia',
        'frequencia',
        'dias_semana',
        'rrule_manual',
    ];

    protected $casts = [
        'responsaveis' => 'array',
        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',
    ];


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

    public function getTemRecorrenciaAttribute()
    {

        return !empty($this->rrule);
    }

    public function getFrequenciaAttribute()
    {
        if (!$this->rrule) {
            return null;
        }


        $rule = Rule::createFromString($this->rrule);
        $freq = $rule->getFreq();

        // Mapeamento das frequências
        $frequencias = [
            0 => 'YEARLY',
            1 => 'MONTHLY',
            2 => 'WEEKLY',
            3 => 'DAILY',
            4 => 'HOURLY',
            5 => 'MINUTELY',
            6 => 'SECONDLY'
        ];

        return $frequencias[$freq] ?? null;
    }

    public function getDiasSemanaAttribute()
    {
        if (!$this->rrule) {
            return [];
        }

        $rule = Rule::createFromString($this->rrule);


        return $rule->getByDay() ?: [];
    }

    public function getRruleManualAttribute()
    {
        return $this->rrule;
    }
}
