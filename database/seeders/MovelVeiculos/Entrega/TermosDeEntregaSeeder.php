<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\TermoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermosDeEntregaSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id = Empresa::MOVEL_VEICULOS_ID;

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $termos = DB::connection('mysql_movelveiculos')
                ->table('termos_de_entrega')
                ->get();

            foreach ($termos as $termo) {


                TermoDeEntrega::updateOrCreate([
                    'id' => $termo->id,
                ], [
                    'entrega_id' => $termo->entregas_id,
                    'teav_presenca_do_estepe_chave_macado_triangulo' => $termo->teav_presenca_do_estepe_chave_macado_triangulo,
                    'teav_recomendar_leitura_manual_seguranca' => $termo->teav_recomendar_leitura_manual_seguranca,
                    'teav_acessorios_instalados' => $termo->teav_acessorios_instalados,
                    'teav_servicos_de_estetica' => $termo->teav_servicos_de_estetica,
                    'teav_funcionamento_do_painel_computador_de_bordo_sistema' => $termo->teav_funcionamento_do_painel_computador_de_bordo_sistema,
                    'teav_app_meu_vw_demonstracao_de_funcionalidades_indica' => $termo->teav_app_meu_vw_demonstracao_de_funcionalidades_indica,
                    'teav_operacao_do_sistema_de_ar_condicionado' => $termo->teav_operacao_do_sistema_de_ar_condicionado,
                    'teav_funcionamento_do_espelho_convexos_travas' => $termo->teav_funcionamento_do_espelho_convexos_travas,
                    'teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro' => $termo->teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro,
                    'teav_operacao_do_limpador_de_parabrisa' => $termo->teav_operacao_do_limpador_de_parabrisa,
                    'teav_funcionamento_do_acendimento_automatico_dos_far' => $termo->teav_funcionamento_do_acendimento_automatico_dos_far,
                    'teav_operacao_dos_ajustes_e_comandos_dos_bancos' => $termo->teav_operacao_dos_ajustes_e_comandos_dos_bancos,
                    'teav_operacao_dos_ajustes_e_da_coluna_de_direcao' => $termo->teav_operacao_dos_ajustes_e_da_coluna_de_direcao,
                    'teav_operacao_de_abertura_do_porta_malas_e_do_bocal' => $termo->teav_operacao_de_abertura_do_porta_malas_e_do_bocal,
                    'teav_funcionamento_do_sistema_flex' => $termo->teav_funcionamento_do_sistema_flex,
                    'teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10' => $termo->teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10,
                    'teav_nota_fiscal' => $termo->teav_nota_fiscal,
                    'teav_chave_reserva' => $termo->teav_chave_reserva,
                    'teav_documentacao_veiculo_com_placa' => $termo->teav_documentacao_veiculo_com_placa,
                    'teav_manuais_proprietario_central_multimidia_e_garantia' => $termo->teav_manuais_proprietario_central_multimidia_e_garantia,
                    'teav_tempo_de_garantia_e_plano_de_manutencao' => $termo->teav_tempo_de_garantia_e_plano_de_manutencao,
                    'teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo' => $termo->teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo,
                    'teav_em_condicoes_de_cobertura_e_itens_nao_cobertos' => $termo->teav_em_condicoes_de_cobertura_e_itens_nao_cobertos,
                    'teav_sistema_de_agendamento_e_apresentacao_do_pos_venda' => $termo->teav_sistema_de_agendamento_e_apresentacao_do_pos_venda,
                    'teav_volkswagen_service_24_horas' => $termo->teav_volkswagen_service_24_horas,
                    'teav_compartimento_do_motor_reservatorios_e_fluidos' => $termo->teav_compartimento_do_motor_reservatorios_e_fluidos,
                    'teav_presenca_de_antena_inclusive_quando_nao' => $termo->teav_presenca_de_antena_inclusive_quando_nao,
                    'teav_sistema_kessy_chave_presencial' => $termo->teav_sistema_kessy_chave_presencial,
                    'teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo' => $termo->teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo,

                    'created_by' => Mapeamento::getNovoId('users', $termo->created_by, $this->bd_origem),
                    'updated_by' => Mapeamento::getNovoId('users', $termo->updated_by, $this->bd_origem),
                    'deleted_by' => Mapeamento::getNovoId('users', $termo->deleted_by, $this->bd_origem),

                    'created_at' => $termo->created_at,
                    'updated_at' => $termo->updated_at,
                    'deleted_at' => $termo->deleted_at,
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
