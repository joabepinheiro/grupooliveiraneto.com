<?php

namespace App\Models\Entrega;

use App\Models\AbstractModel;
use App\Models\User;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
class TermoDeEntrega extends AbstractModel implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsAllActivity;

    protected $table = 'termos_de_entrega';

    protected $fillable = [

        'teav_presenca_do_estepe_chave_macado_triangulo',
        'teav_recomendar_leitura_manual_seguranca',
        'teav_acessorios_instalados',
        'teav_servicos_de_estetica',
        'teav_funcionamento_do_painel_computador_de_bordo_sistema',
        'teav_app_meu_vw_demonstracao_de_funcionalidades_indica',
        'teav_operacao_do_sistema_de_ar_condicionado',
        'teav_funcionamento_do_espelho_convexos_travas',
        'teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro',
        'teav_operacao_do_limpador_de_parabrisa',
        'teav_funcionamento_do_acendimento_automatico_dos_far',
        'teav_operacao_dos_ajustes_e_comandos_dos_bancos',
        'teav_operacao_dos_ajustes_e_da_coluna_de_direcao',
        'teav_operacao_de_abertura_do_porta_malas_e_do_bocal',
        'teav_funcionamento_do_sistema_flex',
        'teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10',

        'teav_nota_fiscal',
        'teav_chave_reserva',
        'teav_documentacao_veiculo_com_placa',

        'acessorios',

        'teav_manuais_proprietario_central_multimidia_e_garantia',
        'teav_tempo_de_garantia_e_plano_de_manutencao',
        'teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo',
        'teav_em_condicoes_de_cobertura_e_itens_nao_cobertos',
        'teav_sistema_de_agendamento_e_apresentacao_do_pos_venda',
        'teav_volkswagen_service_24_horas',
        'teav_compartimento_do_motor_reservatorios_e_fluidos',
        'teav_presenca_de_antena_inclusive_quando_nao',
        'teav_sistema_kessy_chave_presencial',
        'teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
