<?php

namespace App\Models\Tarefa;

use App\Models\AbstractModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ocorrencia extends AbstractModel
{
    protected $table = 'agenda_ocorrencias_tarefas';

    protected $fillable = [
        'tarefa_id',
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'status',
        'responsaveis',
        'is_excecao',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $casts = [
        'responsaveis'        => 'array',
        'data_inicio'        => 'datetime',
        'data_fim'           => 'datetime',
        'is_excecao'         => 'boolean',
    ];

    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class, 'tarefa_id');
    }

    /**
     * Indica se a ocorrência é exceção.
     */
    public function isExcecao(): bool
    {
        return $this->is_excecao === true;
    }

    public function createdByFF()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Retorna o usuário que atualizou o registro.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Retorna o usuário que deletou o registro.
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     *
     *
     *
     * $ocorrencia->update([
     * 'is_excecao' => true,
     * 'dados_sobrescritos' => [
     * 'descricao' => $novaDescricao,
     * 'data_inicio' => $novoInicio,
     * 'data_fim' => $novoFim,
     * 'cor' => $novaCor,
     * 'status' => $novoStatus,
     * // qualquer outro campo sobrescrito
     * ]
     * ]);
     */
}
