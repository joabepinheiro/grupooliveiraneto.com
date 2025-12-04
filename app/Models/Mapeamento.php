<?php

namespace App\Models;

use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Mapeamento das ids para migração de dados dos bancos de dados antigos para o novo banco de dados
 *
 * @property int|null $id_antigo
 * @property int|null $id_novo
 * @property string $table_origem
 * @property string $table_destino
 * @property string|null $bd_origem
 * @property string|null $bd_destino
 * @property string|null $morphable_type
 * @property int|null $morphable_id
 */
class Mapeamento extends Model
{
    protected $table = 'mapeamentos';

    protected static ?string $modelLabel        = 'Mapeamento';
    protected static ?string $pluralModelLabel  = 'Mapeamentos';

    protected $fillable = [
        'id_novo',
        'id_antigo',

        'table_origem',
        'table_destino',

        'bd_origem',

        'dados',
    ];

    protected $casts = [
        'dados' => 'array',

        'id_novo' => 'integer',
        'id_antigo' => 'integer',
        'morphable_id' => 'integer',

        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];



    /*
    |--------------------------------------------------------------------------
    | Static helpers (facilitam muito o uso no ETL)
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna o id_novo baseado no id_antigo.
     */
    public static function getNovoId(string $tabela, $id_antigo, string $bd_origem = null)
    {
        if($id_antigo == NULL){
            return  NULL;
        }

        return Mapeamento::where('table_origem', '=', $tabela)
            ->where('id_antigo', '=', $id_antigo)
            ->where('bd_origem', '=', $bd_origem)
            ->first()->id_novo ?? NULL;
    }

    /**
     * Retorna o id_antigo baseado no id_novo.
     */
    public static function getAntigoId(string $tabela, $id_novo, string $bd_origem = null)
    {
        if($id_novo == NULL){
            return  NULL;
        }

        return Mapeamento::where('table_origem', '=', $tabela)
            ->where('id_novo', '=', $id_novo)
            ->where('bd_origem', '=', $bd_origem)
            ->first()->id_antigo ?? NULL;
    }

    public static function getModelLabel(): string
    {
        return self::$modelLabel;
    }

    public static function getPluralLabel(): ?string
    {
        return self::$pluralModelLabel;
    }
}
