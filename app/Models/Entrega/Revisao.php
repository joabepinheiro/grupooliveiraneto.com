<?php

namespace App\Models\Entrega;

use App\Models\AbstractModel;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
class Revisao extends AbstractModel implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsAllActivity;

    protected $table = 'revisoes';

    protected $fillable = [
        'entregas_id',
        'veiculo_liberado',
        'data_da_inspecao',
        'numero_da_ordem_de_servico',
        'nome_do_responsavel_pela_inspecao',
        'assinatura_inspecao',

        'preparacao_exterior_revisao_de_entrega',
        'preparacao_exterior_elaboracao_do_comprovante_de_servico',
        'preparacao_exterior_pintura_sem_riscos_e_danos',
        'preparacao_interior_funcionamento_do_veiculo',
        'preparacao_interior_marcador',
        'preparacao_interior_central_multimidia',
        'preparacao_interior_verificacao_de_itens',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }


    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class, 'entregas_id', 'id');
    }
}
