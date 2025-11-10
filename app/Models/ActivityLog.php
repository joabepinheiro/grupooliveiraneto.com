<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    /**
     * Nome da tabela associado ao modelo.
     */
    protected $table = 'activity_log'; // Alterar se `config('activitylog.table_name')` for diferente

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Relacionamento polimórfico para o sujeito associado à atividade.
     *
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /**
     * Relacionamento polimórfico para o causador da atividade.
     *
     * @return MorphTo
     */
    public function causer(): MorphTo
    {
        return $this->morphTo('causer');
    }
}
