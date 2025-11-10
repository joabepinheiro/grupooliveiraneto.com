<?php

namespace App\Models\Entrega;

use App\Models\AbstractModel;
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
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
class Entrega extends AbstractModel implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsAllActivity;

    protected $table = 'entregas';

    protected $fillable = [

        'solicitacoes_de_entrega_id',

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
        'observacoes',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'fotos' => 'array',
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

    public function financeiroAutorizadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financeiro_autorizada_pelo_financeiro_por');
    }


    public function revisao_de_entrega(): HasOne
    {
        return $this->hasOne(Revisao::class, 'entregas_id');
    }

    public function termo_de_entrega(): HasOne
    {
        return $this->hasOne(TermoDeEntrega::class, 'entregas_id');
    }


    public function acessorios(): HasMany
    {
        return $this->hasMany(Acessorio::class, 'entregas_id', 'id');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id', 'id');
    }

    public function notas_da_orientacao_cem(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'orientacao_cem');
    }

    public function notas_da_orientacao_csi(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'orientacao_csi');
    }


}
