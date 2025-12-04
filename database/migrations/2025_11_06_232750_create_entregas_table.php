<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_id')->nullable();

            $table->unsignedBigInteger('solicitacoes_de_entrega_id')->nullable();
            $table->string('status')->default('Em andamento');
            $table->string('tipo_venda')->nullable();

            $table->dateTime('data_prevista')->nullable();
            $table->dateTime('entrega_efetivada_em')->nullable();
            $table->string('proposta')->nullable();
            $table->string('cliente')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();

            $table->string('modelo')->nullable();
            $table->string('cor')->nullable();
            $table->string('chassi')->nullable();
            $table->string('brinde')->nullable();

            $table->boolean('foi_solicitado_emplacamento')->nullable();
            $table->boolean('foi_solicitado_acessorio')->nullable();
            $table->text('acessorios_solicitados')->nullable();

            //Faturamento
            $table->boolean('financiamento_e_seguro')->nullable();
            $table->boolean('faturamento')->nullable();

            //Veículo na troca
            $table->boolean('segundo_veiculo_na_troca_status')->nullable();

            //Documentação
            $table->text('documentacao')->nullable();

            $table->boolean('documentacao_nota_fiscal')->nullable();
            $table->boolean('documentacao_documentacao_veiculo_com_placa')->nullable();
            $table->boolean('documentacao_chave_reserva')->nullable();
            $table->boolean('documentacao_manuais')->nullable();
            $table->boolean('documentacao_carregador')->nullable(); //Só no BYD
            $table->boolean('documentacao_kit_reparo_ou_step')->nullable(); //Só no BYD


            //$table->text('fotos')->nullable();


            //Serviços estéticos
            $table->boolean('servicos_adicionais_lavagem')->nullable();
            $table->boolean('servicos_adicionais_combustivel')->nullable();
            $table->boolean('servicos_adicionais_recarga')->nullable(); // Só no BYD

            //Financeiro
            $table->boolean('financeiro_autorizada_pelo_financeiro')->nullable();
            $table->unsignedBigInteger('financeiro_autorizada_pelo_financeiro_por')->nullable();
            $table->string('financeiro_forma_de_pagamento')->nullable();
            //$table->text('comprovantes_de_pagamento')->nullable();
            $table->longText('financeiro_assinatura')->nullable();


            $table->text('observacoes')->nullable();

            $table->boolean('autorizo_uso_de_minha_imagem_em_publicidade')->nullable();
            $table->boolean('cliente_se_recusou_a_receber_as_informacoes')->nullable();
            $table->boolean('cliente_foi_orientado_a_baixar_o_aplicativo')->nullable();

            $table->string('aceita_ativacao_conectividade_carro_conectado')->nullable();
            $table->boolean('cliente_se_recusou_a_receber_as_informacoes_contidas')->nullable();
            $table->boolean('aceite_termo')->nullable();


            //Assinatura do cliente
            $table->longText('assinatura_do_cliente')->nullable();


            $table->boolean('orientacao_cem_finalizada')->nullable();
            $table->boolean('orientacao_csi_finalizada')->nullable();


            $table->boolean('byd_acessorios_higienizacao')->nullable();
            $table->boolean('byd_acessorios_polimento')->nullable();


            // [BYD] Encantamento e instrução (12 horas antes)
            $table->boolean('byd_encantamento_e_instrucao_carro_no_showroom')->nullable();
            $table->boolean('byd_encantamento_e_instrucao_capa_e_laco')->nullable();
            $table->boolean('byd_encantamento_e_instrucao_brindes')->nullable();
            $table->boolean('byd_encantamento_e_instrucao_musica')->nullable();

            // [BYD] Preparação do veículo (48 horas antes)
            $table->boolean('byd_preparacao_exterior_revisao_de_entrega')->nullable();
            $table->boolean('byd_preparacao_exterior_elaboracao_do_comprovante_de_servico')->nullable();
            $table->boolean('byd_preparacao_exterior_pintura_sem_riscos_e_danos')->nullable();
            $table->boolean('byd_preparacao_interior_funcionamento_do_veiculo')->nullable();
            $table->boolean('byd_preparacao_interior_marcador')->nullable();
            $table->boolean('byd_preparacao_interior_central_multimidia')->nullable();
            $table->boolean('byd_preparacao_interior_verificacao_de_itens')->nullable();

            // [BYD] Pesquisa
            $table->text('byd_pesquisa_com_7_dias')->nullable();
            $table->boolean('byd_pesquisa_com_7_dias_finalizada')->nullable();
            $table->text('byd_pesquisa_com_30_dias')->nullable();
            $table->boolean('byd_pesquisa_com_30_dias_finalizada')->nullable();



            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

















        });

        Schema::create('entrega_acessorios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrega_id')->nullable();
            $table->string('descricao')->nullable();
            $table->boolean('instalado')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('entrega_revisoes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrega_id')->nullable();

            $table->boolean('veiculo_liberado')->nullable();
            $table->dateTime('data_da_inspecao')->nullable();
            $table->text('numero_da_ordem_de_servico')->nullable();
            $table->text('nome_do_responsavel_pela_inspecao')->nullable();
            $table->longText('assinatura_inspecao')->nullable();
            $table->boolean('preparacao_exterior_revisao_de_entrega')->nullable();
            $table->boolean('preparacao_exterior_elaboracao_do_comprovante_de_servico')->nullable();
            $table->boolean('preparacao_exterior_pintura_sem_riscos_e_danos')->nullable();
            $table->boolean('preparacao_interior_funcionamento_do_veiculo')->nullable();
            $table->boolean('preparacao_interior_marcador')->nullable();
            $table->boolean('preparacao_interior_central_multimidia')->nullable();
            $table->boolean('preparacao_interior_verificacao_de_itens')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('entrega_termos_de_entrega', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrega_id')->nullable();

            $table->string('teav_presenca_do_estepe_chave_macado_triangulo')->nullable();
            $table->string('teav_recomendar_leitura_manual_seguranca')->nullable();
            $table->text('teav_acessorios_instalados')->nullable();
            $table->text('teav_servicos_de_estetica')->nullable();
            $table->string('teav_funcionamento_do_painel_computador_de_bordo_sistema')->nullable();
            $table->string('teav_app_meu_vw_demonstracao_de_funcionalidades_indica')->nullable();
            $table->string('teav_operacao_do_sistema_de_ar_condicionado')->nullable();
            $table->string('teav_funcionamento_do_espelho_convexos_travas')->nullable();
            $table->string('teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro')->nullable();
            $table->string('teav_operacao_do_limpador_de_parabrisa')->nullable();
            $table->string('teav_funcionamento_do_acendimento_automatico_dos_far')->nullable();
            $table->string('teav_operacao_dos_ajustes_e_comandos_dos_bancos')->nullable();
            $table->string('teav_operacao_dos_ajustes_e_da_coluna_de_direcao')->nullable();
            $table->string('teav_operacao_de_abertura_do_porta_malas_e_do_bocal')->nullable();
            $table->string('teav_funcionamento_do_sistema_flex')->nullable();
            $table->string('teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10')->nullable();

            $table->string('teav_nota_fiscal')->nullable();
            $table->string('teav_chave_reserva')->nullable();
            $table->string('teav_documentacao_veiculo_com_placa')->nullable();

            $table->string('teav_manuais_proprietario_central_multimidia_e_garantia')->nullable();
            $table->string('teav_tempo_de_garantia_e_plano_de_manutencao')->nullable();
            $table->string('teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo')->nullable();
            $table->string('teav_em_condicoes_de_cobertura_e_itens_nao_cobertos')->nullable();
            $table->string('teav_sistema_de_agendamento_e_apresentacao_do_pos_venda')->nullable();
            $table->string('teav_volkswagen_service_24_horas')->nullable();
            $table->string('teav_compartimento_do_motor_reservatorios_e_fluidos')->nullable();
            $table->string('teav_presenca_de_antena_inclusive_quando_nao')->nullable();
            $table->string('teav_sistema_kessy_chave_presencial')->nullable();
            $table->string('teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('entrega_segundo_veiculo_na_troca', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrega_id')->nullable();

            $table->string('nome')->nullable();
            $table->string('chassi')->nullable();
            $table->string('ano')->nullable();
            $table->string('modelo')->nullable();
            $table->string('preco')->nullable();
            $table->string('observacao')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_segundo_veiculo_na_troca');
        Schema::dropIfExists('entrega_termos_de_entrega');
        Schema::dropIfExists('entrega_revisoes');
        Schema::dropIfExists('entrega_acessorios');
        Schema::dropIfExists('entregas');;
    }
};
