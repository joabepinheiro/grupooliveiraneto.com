<?php

namespace App\Models;

use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Empresa';
    protected static ?string $pluralModelLabel  = 'Empresas';

    protected $fillable = [
        'id',
        'nome',
        'panel_id',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    const MOVEL_GRUPO_OLIVEIRA_NETO_ID = 1;
    const MOVEL_VEICULOS_ID = 2;
    const BYD_CONQUISTA_ID = 3;
    const MOVEL_CAMINHOES_ID = 4;
}
