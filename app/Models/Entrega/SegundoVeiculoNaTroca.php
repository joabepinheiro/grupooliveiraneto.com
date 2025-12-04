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
class SegundoVeiculoNaTroca extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Segundo veículo na troca';
    protected static ?string $pluralModelLabel  = 'Segundo veículo na troca';


    protected $table = 'entrega_segundo_veiculo_na_troca';

    protected $fillable = [

        'entrega_id',

        'nome',
        'chassi',
        'ano',
        'modelo',
        'preco',
        'observacao',

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

}
