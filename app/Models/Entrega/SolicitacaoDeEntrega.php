<?php

namespace App\Models\Entrega;

use App\Enums\SolicitacaoDeEntregaStatus;
use App\Models\AbstractModel;
use App\Models\Empresa;
use App\Models\User;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
class SolicitacaoDeEntrega extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Solicitação de entrega';
    protected static ?string $pluralModelLabel  = 'Solicitações de entrega';


    protected $table = 'solicitacoes_de_entrega';

    protected $fillable = [
        //Dados gerais
        'empresa_id',
        'entrega_id',
        'status',
        'tipo_venda',
        'data_prevista',
        'entrega_efetivada_em',
        'proposta',
        'cliente',
        'vendedor_id',
        'modelo',
        'cor',
        'chassi',

        'foi_solicitado_emplacamento',
        'foi_solicitado_acessorio',
        'acessorios_solicitados',
        'brinde',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'acessorios_solicitados'    => 'array',
        'data_prevista'             => 'datetime',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
    ];

    protected $dates = [
        'data_prevista',
        'created_at',
        'updated_at',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id', 'id');
    }
}
