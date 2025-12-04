<div>
    <?php $termo = $getRecord(); ?>

    <table class="table-auto w-full border-collapse border-1 border-black text-sm  my-4">
        <tbody>
        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-4 font-bold">Itens obrigatórios e de segurança</td>
        </tr>

        <tr>
            <td class="border border-gray-300 px-4 py-2">Presença do estepe, chave de roda, macaco e triângulo de segurança</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_presenca_do_estepe_chave_macado_triangulo}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Recomendar expressamente a leitura do capítulo de segurança no manual do proprietário</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_recomendar_leitura_manual_seguranca}}</td>
        </tr>
        </tbody>
    </table>


    <table class="w-full border-collapse border border-gray-300 text-sm my-4">
        <tbody>
        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-3 font-bold">Acessórios e serviços de estética</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Acessórios instalados</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_acessorios_instalados}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Serviços de estética realizados</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_servicos_de_estetica}}</td>
        </tr>
        </tbody>
    </table>

    <table class="w-full border-collapse border border-gray-300 text-sm my-4">
        <tbody>
        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-4 font-bold">Interior</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Funcionamento do painel, computador de bordo, sistema de som ou central multimídia ( ex: conectividade, câmera de ré, gps, sd card, etc.)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_funcionamento_do_painel_computador_de_bordo_sistema}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">App meu vw demonstração de funcionalidades, indicação de concessiónaria favorita e, se necessário, auxílio na instalação configuração</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_app_meu_vw_demonstracao_de_funcionalidades_indica}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação do sistema de ar-condicionado: manual e/ou automático (desembaçamento dos vidros)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_do_sistema_de_ar_condicionado}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Funcionamento do espelho convexos, travas, vidros elétricos, com função tilt down e alarme</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_funcionamento_do_espelho_convexos_travas}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação do sistema de controle de velocidade de cruzeiro</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação do limpador de parabrisa (ex: temporizador e sensor de chuva)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_do_limpador_de_parabrisa}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Funcionamento do acendimento automático dos faróis e luzes de conversão</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_funcionamento_do_acendimento_automatico_dos_far}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação dos ajustes e comandos dos bancos</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_dos_ajustes_e_comandos_dos_bancos}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação dos ajustes e da coluna de direção</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_dos_ajustes_e_da_coluna_de_direcao}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Operação de abertura do porta-malas e do bocal</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_operacao_de_abertura_do_porta_malas_e_do_bocal}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Funcionamento do sistema flex (5km)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_funcionamento_do_sistema_flex}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Se motor a diesel orientar quanto ao uso diesel s10 e aditivos/estabilizadores recomendados</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10}}</td>
        </tr>
        </tbody>
    </table>

    <table class="w-full border-collapse border border-gray-300 text-sm my-4">
        <tbody>
        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-2 font-bold">Documentação e garantia</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Nota fiscal</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_nota_fiscal}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Chave reserva</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_chave_reserva}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Documentação/veículo com placa</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_documentacao_veiculo_com_placa}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Manuais: proprietário, central multimidía e garantia/revisão (app meu vw e/ou impresso)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_manuais_proprietario_central_multimidia_e_garantia}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Tempo de garantia e plano de manutenção( a cada 10.000km ou 12 meses)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_tempo_de_garantia_e_plano_de_manutencao}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Em condição severa, orientação de uso, de trocas de óleo e de manutenção</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Em condições de cobertura e itens não cobertos pela garantia.itens de desgaste, bateria, uso severo.</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_em_condicoes_de_cobertura_e_itens_nao_cobertos}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Sistema de agendamento e apresentação do pós venda (nec. aprofundamento)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_sistema_de_agendamento_e_apresentacao_do_pos_venda}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Volkswagen service 24 horas ( número,acesso via vw play e condições gerais)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_volkswagen_service_24_horas}}</td>
        </tr>
        </tbody>
    </table>

    <table class="w-full border-collapse border border-gray-300 text-sm my-4">
        <tbody>
        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-4 font-bold">Exterior</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Compartimento do motor (reservatórios e fluídos)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_compartimento_do_motor_reservatorios_e_fluidos}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Presença de antena ( inclusive quando não for visível)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_presenca_de_antena_inclusive_quando_nao}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Sistema kessy-chave presencial</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_sistema_kessy_chave_presencial}}</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">Uso da "lâmina" da chave para acesso ao veículo (chave sem bateria)</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo}}</td>
        </tr>
        </tbody>
    </table>


    <table class="w-full border-collapse border border-gray-300 text-sm my-4">
        <tbody>

        <tr>
            <td colspan="2" class="border border-gray-300 px-4 py-4 font-bold">Uso de imagem/vídeo e tratamento de dados</td>
        </tr>

        <tr class="bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">AUTORIZO a utilização de minha imagem e/ou vídeo, integralmente ou em parte, desde a presente data, em caráter gratuito, para utilização em trabalhos de publicidade e/ou divulgação comercial (mídias eletrônicas). Por ser esta a expressão de minha vontade, nada terei a reclamar a título de direitos conexos à minha imagem ou qualquer outro. Declaro ainda que estou ciente e concordo com o tratamento de meus dados de acordo com a Política de Privacidade da concessionária supracitada.</td>
            <td class="border border-gray-300 px-4 py-2">{{$termo->autorizo_uso_de_minha_imagem_em_publicidade}}</td>
        </tr>
        </tbody>
    </table>


</div>
