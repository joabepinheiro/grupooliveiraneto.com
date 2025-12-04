<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\Acessorio;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcessorioSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id =  Empresa::MOVEL_VEICULOS_ID;

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $acessorios = DB::connection('mysql_movelveiculos')
                ->table('acessorios')
                ->get();

            foreach ($acessorios as $acessorio) {

                Acessorio::updateOrCreate([
                    'id' => $acessorio->id,
                ], [
                    'entrega_id'                  => $acessorio->entregas_id,
                    'descricao'                   => $acessorio->descricao,
                    'instalado'                   => $acessorio->instalado,

                    'created_by'    => Mapeamento::getNovoId('users', $acessorio->created_by, $this->bd_origem),
                    'updated_by'    => Mapeamento::getNovoId('users', $acessorio->updated_by, $this->bd_origem),
                    'deleted_by'    => Mapeamento::getNovoId('users', $acessorio->deleted_by, $this->bd_origem),

                    'created_at' => $acessorio->created_at,
                    'updated_at' => $acessorio->updated_at,
                    'deleted_at' => $acessorio->deleted_at,
                ]);
            }
        }
        catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        }
        finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
