<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\SegundoVeiculoNaTroca;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegundoVeiculoNaTrocaSeeder extends Seeder
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
            $entregas = DB::connection('mysql_movelveiculos')
                ->table('entregas')
                ->get();

            foreach ($entregas as $entrega) {

                SegundoVeiculoNaTroca::updateOrCreate([
                    'id' => $entrega->id,
                ], [

                    'entrega_id'             => $entrega->id,
                    'nome'                   => $entrega->segundo_veiculo_na_troca_nome,
                    'chassi'                 => $entrega->segundo_veiculo_na_troca_chassi,
                    'ano'                    => $entrega->segundo_veiculo_na_troca_ano,
                    'modelo'                 => $entrega->segundo_veiculo_na_troca_modelo,
                    'preco'                  => $entrega->segundo_veiculo_na_troca_preco,
                    'observacao'             => $entrega->segundo_veiculo_na_troca_observacao,

                    'created_by'    => Mapeamento::getNovoId('users', $entrega->created_by, $this->bd_origem),
                    'updated_by'    => Mapeamento::getNovoId('users', $entrega->updated_by, $this->bd_origem),
                    'deleted_by'    => Mapeamento::getNovoId('users', $entrega->deleted_by, $this->bd_origem),

                    'created_at' => $entrega->created_at,
                    'updated_at' => $entrega->updated_at,
                    'deleted_at' => $entrega->deleted_at,
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
