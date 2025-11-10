<?php

namespace App\Models;

use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends AbstractModel
{
    use SoftDeletes;
    use LogsAllActivity;

    protected $fillable = [
        'id',
        'nome',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const MOVEL_GRUPO_OLIVEIRA_NETO_ID = 1;
    const MOVEL_VEICULOS_ID = 2;
    const BYD_CONQUISTA_ID = 3;
    const MOVEL_CAMINHOES_ID = 4;
}
