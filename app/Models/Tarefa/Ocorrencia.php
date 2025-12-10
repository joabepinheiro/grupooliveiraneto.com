<?php

namespace App\Models\Tarefa;

use App\Models\AbstractModel;
use App\Models\User;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocorrencia extends AbstractModel
{

    use SoftDeletes;
    use LogsAllActivity;

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
}
