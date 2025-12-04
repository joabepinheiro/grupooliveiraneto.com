<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\Revisao;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevisaoSeeder extends Seeder
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
            $revisoes = DB::connection('mysql_movelveiculos')
                ->table('revisoes')
                ->get();

            foreach ($revisoes as $revisao) {

                Revisao::updateOrCreate([
                    'id' => $revisao->id,
                ], [
                    'entrega_id'                                   => $revisao->entregas_id,
                    'veiculo_liberado'                              => $revisao->veiculo_liberado,
                    'data_da_inspecao'                              => $revisao->data_da_inspecao,
                    'numero_da_ordem_de_servico'                    => $revisao->numero_da_ordem_de_servico,
                    'nome_do_responsavel_pela_inspecao'             => $revisao->nome_do_responsavel_pela_inspecao,
                    'assinatura_inspecao'                           => $revisao->assinatura_inspecao,
                    'preparacao_exterior_revisao_de_entrega'        => $revisao->preparacao_exterior_revisao_de_entrega,
                    'preparacao_exterior_elaboracao_do_comprovante_de_servico'      => $revisao->preparacao_exterior_elaboracao_do_comprovante_de_servico,
                    'preparacao_exterior_pintura_sem_riscos_e_danos'                => $revisao->preparacao_exterior_pintura_sem_riscos_e_danos,
                    'preparacao_interior_funcionamento_do_veiculo'                  => $revisao->preparacao_interior_funcionamento_do_veiculo,
                    'preparacao_interior_marcador'                                  => $revisao->preparacao_interior_marcador,
                    'preparacao_interior_central_multimidia'                        => $revisao->preparacao_interior_central_multimidia,
                    'preparacao_interior_verificacao_de_itens'                      => $revisao->preparacao_interior_verificacao_de_itens,

                    'created_by'    => Mapeamento::getNovoId('users', $revisao->created_by, $this->bd_origem),
                    'updated_by'    => Mapeamento::getNovoId('users', $revisao->updated_by, $this->bd_origem),
                    'deleted_by'    => Mapeamento::getNovoId('users', $revisao->deleted_by, $this->bd_origem),

                    'created_at' => $revisao->created_at,
                    'updated_at' => $revisao->updated_at,
                    'deleted_at' => $revisao->deleted_at,
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
