<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntregaSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id =  Empresa::MOVEL_VEICULOS_ID;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar foreign key checks temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $entregas = DB::connection('mysql_movelveiculos')
                ->table('entregas')
                ->get();

            foreach ($entregas as $entrega) {

                // Criando no novo sistema
                $novo = Entrega::updateOrCreate([
                    'id' => $entrega->id,
                ], [

                    'empresa_id' => $this->empresa_id,

                    'solicitacoes_de_entrega_id' => $entrega->solicitacoes_de_entrega_id,
                    'status' => $entrega->status,
                    'tipo_venda' => $entrega->tipo_venda,
                    'data_prevista' => $entrega->data_prevista,
                    'entrega_efetivada_em' => $entrega->entrega_efetivada_em,
                    'proposta' => $entrega->proposta,
                    'cliente' => $entrega->cliente,
                    'vendedor_id' => Mapeamento::getNovoId('users', $entrega->vendedor_id, $this->bd_origem),
                    'modelo' => $entrega->modelo,
                    'cor' => $entrega->cor,
                    'chassi' => $entrega->chassi,
                    'brinde' => $entrega->brinde,
                    'foi_solicitado_emplacamento' => $entrega->foi_solicitado_emplacamento,
                    'foi_solicitado_acessorio' => $entrega->foi_solicitado_acessorio,
                    'acessorios_solicitados' => json_decode($entrega->acessorios_solicitados, true),
                    'financiamento_e_seguro' => $entrega->financiamento_e_seguro,
                    'faturamento' => $entrega->faturamento,
                    'segundo_veiculo_na_troca_status' => $entrega->segundo_veiculo_na_troca_status,
                    'documentacao_nota_fiscal' => $entrega->documentacao_nota_fiscal,
                    'documentacao_documentacao_veiculo_com_placa' => $entrega->documentacao_documentacao_veiculo_com_placa,
                    'documentacao_chave_reserva' => $entrega->documentacao_chave_reserva,
                    'documentacao_manuais' => $entrega->documentacao_manuais,
                    //'fotos'    => $entrega->fotos,
                    'servicos_adicionais_lavagem' => $entrega->servicos_adicionais_lavagem,
                    'servicos_adicionais_combustivel' => $entrega->servicos_adicionais_combustivel,
                    'financeiro_autorizada_pelo_financeiro' => $entrega->financeiro_autorizada_pelo_financeiro,
                    'financeiro_autorizada_pelo_financeiro_por' => $entrega->financeiro_autorizada_pelo_financeiro_por,
                    'financeiro_forma_de_pagamento' => $entrega->financeiro_forma_de_pagamento,
                    //'comprovantes_de_pagamento'    => $entrega->comprovantes_de_pagamento,
                    'financeiro_assinatura' => $entrega->financeiro_assinatura,
                    'observacoes' => $entrega->observacoes,
                    'autorizo_uso_de_minha_imagem_em_publicidade' => $entrega->autorizo_uso_de_minha_imagem_em_publicidade,
                    'cliente_se_recusou_a_receber_as_informacoes' => $entrega->cliente_se_recusou_a_receber_as_informacoes,
                    'cliente_foi_orientado_a_baixar_o_aplicativo' => $entrega->cliente_foi_orientado_a_baixar_o_aplicativo,
                    'aceita_ativacao_conectividade_carro_conectado' => $entrega->aceita_ativacao_conectividade_carro_conectado,
                    'cliente_se_recusou_a_receber_as_informacoes_contidas' => $entrega->cliente_se_recusou_a_receber_as_informacoes_contidas,
                    'aceite_termo' => $entrega->aceite_termo,
                    'assinatura_do_cliente' => $entrega->assinatura_do_cliente,
                    'orientacao_cem_finalizada' => $entrega->orientacao_cem_finalizada,
                    'orientacao_csi_finalizada' => $entrega->orientacao_csi_finalizada,

                    'created_by' => Mapeamento::getNovoId('users', $entrega->created_by, $this->bd_origem),
                    'updated_by' => Mapeamento::getNovoId('users', $entrega->updated_by, $this->bd_origem),
                    'deleted_by' => Mapeamento::getNovoId('users', $entrega->deleted_by, $this->bd_origem),

                    'created_at' => $entrega->created_at,
                    'updated_at' => $entrega->updated_at,
                    'deleted_at' => $entrega->deleted_at,
                ]);

                Mapeamento::create([
                    'id_novo' => $novo->id,
                    'id_antigo' => $entrega->id,
                    'table_origem' => 'entregas',
                    'table_destino' => 'entregas',
                    'bd_origem' => $this->bd_origem,
                    'dados' => $entrega,
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        } finally {
            // Reabilitar foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }


}
