<?php

namespace Database\Seeders\BYDConquista;

use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use App\Models\Entrega\Revisao;
use App\Models\Entrega\TermoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar foreign key checks temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $entregasBYD= DB::connection('mysql_bydconquista')
                ->table('entregas')
                ->get();

            foreach ($entregasBYD as $entrega) {
                // Criando no novo sistema
                $novo = Entrega::updateOrCreate([
                    'id' => null,
                ], [

                    'empresa_id'    => Empresa::BYD_CONQUISTA_ID,
                    'status'        => $entrega->status,
                    'tipo_venda'    => $entrega->tipo_venda,
                    'data_prevista' => $entrega->data_prevista,
                    'entrega_efetivada_em'    => $entrega->entrega_efetivada_em,
                    'proposta'      => $entrega->proposta,
                    'cliente'       => $entrega->cliente,
                    'vendedor_id'   => $entrega->vendedor_id,
                    'modelo'        => $entrega->modelo,
                    'cor'           => $entrega->cor,
                    'chassi'        => $entrega->chassi,
                    'brinde'        => $entrega->brinde,
                    'foi_solicitado_emplacamento'   => $entrega->foi_solicitado_emplacamento,
                    'foi_solicitado_acessorio'      => $entrega->foi_solicitado_acessorio,
                    'acessorios_solicitados' => json_decode($entrega->acessorios_solicitados, true),
                    'financiamento_e_seguro'        => $entrega->financiamento_e_seguro,
                    'faturamento'                   => $entrega->faturamento,

                    'documentacao_nota_fiscal'                      => $entrega->documentacao_nota_fiscal,
                    'documentacao_documentacao_veiculo_com_placa'   => $entrega->documentacao_documentacao_veiculo_com_placa,
                    'documentacao_chave_reserva'                    => $entrega->documentacao_chave_reserva,
                    'documentacao_manuais'                          => $entrega->documentacao_manuais,

                    'servicos_adicionais_lavagem'               => $entrega->servicos_adicionais_lavagem,

                    'financeiro_autorizada_pelo_financeiro'         => $entrega->financeiro_autorizada_pelo_financeiro,
                    'financeiro_autorizada_pelo_financeiro_por'     => $entrega->financeiro_autorizada_pelo_financeiro_por,
                    'financeiro_forma_de_pagamento'                 => $entrega->financeiro_forma_de_pagamento,

                    'financeiro_assinatura'     => $entrega->assinatura,
                    'observacoes'               => $entrega->observacoes,



                    'byd_acessorios_higienizacao'   => $entrega->acessorios_higienizacao,
                    'byd_acessorios_polimento'      => $entrega->acessorios_polimento,

                    // [BYD] Encantamento e instrução (12 horas antes)
                    'byd_encantamento_e_instrucao_carro_no_showroom'    => $entrega->encantamento_e_instrucao_carro_no_showroom,
                    'byd_encantamento_e_instrucao_capa_e_laco'          => $entrega->encantamento_e_instrucao_capa_e_laco,
                    'byd_encantamento_e_instrucao_brindes'              => $entrega->encantamento_e_instrucao_brindes,
                    'byd_encantamento_e_instrucao_musica'               => $entrega->encantamento_e_instrucao_musica,

                    // [BYD] Preparação do veículo (48 horas antes)
                    'byd_preparacao_exterior_revisao_de_entrega'                    => $entrega->preparacao_exterior_revisao_de_entrega,
                    'byd_preparacao_exterior_elaboracao_do_comprovante_de_servico'  => $entrega->preparacao_exterior_elaboracao_do_comprovante_de_servico,
                    'byd_preparacao_exterior_pintura_sem_riscos_e_danos'            => $entrega->preparacao_exterior_pintura_sem_riscos_e_danos,
                    'byd_preparacao_interior_funcionamento_do_veiculo'              => $entrega->preparacao_interior_funcionamento_do_veiculo,
                    'byd_preparacao_interior_marcador'                              => $entrega->preparacao_interior_marcador,
                    'byd_preparacao_interior_central_multimidia'                    => $entrega->preparacao_interior_central_multimidia,
                    'byd_preparacao_interior_verificacao_de_itens'                  => $entrega->preparacao_interior_verificacao_de_itens,

                    // [BYD] Pesquisa
                    'byd_pesquisa_com_7_dias'               => $entrega->pesquisa_com_7_dias,
                    'byd_pesquisa_com_7_dias_finalizada'    => $entrega->pesquisa_com_7_dias_finalizada,
                    'byd_pesquisa_com_30_dias'              => $entrega->pesquisa_com_30_dias,
                    'byd_pesquisa_com_30_dias_finalizada'   => $entrega->pesquisa_com_30_dias_finalizada,


                    'created_by'    => Mapeamento::getNovoId('users', $entrega->created_by, 'bydconquista'),
                    'updated_by'    => Mapeamento::getNovoId('users', $entrega->updated_by, 'bydconquista'),
                    'deleted_by'    => Mapeamento::getNovoId('users', $entrega->deleted_by, 'bydconquista'),

                    'created_at' => $entrega->created_at,
                    'updated_at' => $entrega->updated_at,
                    'deleted_at' => $entrega->deleted_at,


                ]);

                Mapeamento::create([
                    'id_novo'       => $novo->id,
                    'id_antigo'     => $entrega->id,
                    'table_origem'  => 'entregas',
                    'table_destino' => 'entregas'  ,
                    'bd_origem'     => 'bydconquista',
                    'dados'         => $entrega,
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
