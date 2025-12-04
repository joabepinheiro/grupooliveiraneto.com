<?php

namespace Database\Seeders\BYDConquista;

use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitacaoDeEntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Desabilitar foreign key checks temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $solicitacoesMovel= DB::connection('mysql_bydconquista')
                ->table('solicitacoes_de_entrega')
                ->get();

            foreach ($solicitacoesMovel as $solicitacao) {
                // Criando no novo sistema
                $novo = SolicitacaoDeEntrega::updateOrCreate([
                    'id' => null,
                ], [
                    'empresa_id'    => 3,

                    'entrega_id'    => $solicitacao->entrega_id,
                    'status'        => $solicitacao->status,
                    'tipo_venda'    => $solicitacao->tipo_venda,
                    'data_prevista' => $solicitacao->data_prevista == '0000-00-00 00:00:00' ? null : $solicitacao->data_prevista,
                    'entrega_efetivada_em' => $solicitacao->entrega_efetivada_em,
                    'proposta'      => $solicitacao->proposta,
                    'cliente'       => $solicitacao->cliente,
                    'vendedor_id'   => $solicitacao->vendedor_id,
                    'modelo'        => $solicitacao->modelo,
                    'cor'           => $solicitacao->cor,
                    'chassi'        => $solicitacao->chassi,
                    'foi_solicitado_emplacamento' => $solicitacao->foi_solicitado_emplacamento,
                    'foi_solicitado_acessorio' => $solicitacao->foi_solicitado_acessorio ?? 0,
                    'acessorios_solicitados' => json_decode($solicitacao->acessorios_solicitados, true),
                    'brinde' => $solicitacao->brinde,

                    'created_by'    => Mapeamento::getNovoId('users', $solicitacao->created_by, 'bydconquista'),
                    'updated_by'    => Mapeamento::getNovoId('users', $solicitacao->updated_by, 'bydconquista'),
                    'deleted_by'    => Mapeamento::getNovoId('users', $solicitacao->deleted_by, 'bydconquista'),

                    'created_at' => $solicitacao->created_at,
                    'updated_at' => $solicitacao->updated_at,
                    'deleted_at' => $solicitacao->deleted_at,
                ]);

                Mapeamento::create([
                    'id_novo'       => $novo->id,
                    'id_antigo'     => $solicitacao->id,
                    'table_origem'  => 'solicitacoes_de_entrega',
                    'table_destino' => 'solicitacoes_de_entrega'  ,
                    'bd_origem'     => 'bydconquista',
                    'dados'         => $solicitacao,
                ]);
            }
        }
        catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        }
        finally {
            // Reabilitar foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
