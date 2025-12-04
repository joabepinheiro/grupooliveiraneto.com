<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitacaoDeEntregaSeeder extends Seeder
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
            $solicitacoes_de_entrega = DB::connection('mysql_movelveiculos')
                ->table('solicitacoes_de_entrega')
                ->get();

            foreach ($solicitacoes_de_entrega as $solicitacao) {

                SolicitacaoDeEntrega::updateOrCreate([
                    'id' => $solicitacao->id,
                ], [

                    'empresa_id'                    => $this->empresa_id,
                    'entrega_id'                    => $solicitacao->entrega_id,
                    'status'                        => $solicitacao->status,
                    'tipo_venda'                    => $solicitacao->tipo_venda,
                    'data_prevista'                 => $solicitacao->data_prevista == '0000-00-00 00:00:00' ? null : $solicitacao->data_prevista,
                    'entrega_efetivada_em'          => $solicitacao->entrega_efetivada_em,
                    'proposta'                      => $solicitacao->proposta,
                    'cliente'                       => $solicitacao->cliente,
                    'vendedor_id'                   => Mapeamento::getNovoId('users', $solicitacao->vendedor_id, $this->bd_origem),
                    'modelo'                        => $solicitacao->modelo,
                    'cor'                           => $solicitacao->cor,
                    'chassi'                        => $solicitacao->chassi,
                    'foi_solicitado_emplacamento'   => $solicitacao->foi_solicitado_emplacamento,
                    'foi_solicitado_acessorio'      => $solicitacao->foi_solicitado_acessorio ?? 0,
                    'acessorios_solicitados'        => json_decode($solicitacao->acessorios_solicitados, true),
                    'brinde'                        => $solicitacao->brinde,

                    'created_by'    => Mapeamento::getNovoId('users', $solicitacao->created_by, $this->bd_origem),
                    'updated_by'    => Mapeamento::getNovoId('users', $solicitacao->updated_by, $this->bd_origem),
                    'deleted_by'    => Mapeamento::getNovoId('users', $solicitacao->deleted_by, $this->bd_origem),

                    'created_at' => $solicitacao->created_at,
                    'updated_at' => $solicitacao->updated_at,
                    'deleted_at' => $solicitacao->deleted_at,
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
