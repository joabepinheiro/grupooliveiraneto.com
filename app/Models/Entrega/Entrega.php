<?php

namespace App\Models\Entrega;

use App\Enums\EntregaStatus;
use App\Models\AbstractModel;
use App\Models\ActivityLog;
use App\Models\User;
use App\Traits\LogsAllActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
class Entrega extends AbstractModel implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsAllActivity;

    protected static ?string $modelLabel        = 'Entrega';
    protected static ?string $pluralModelLabel  = 'Entregas';

    protected $table = 'entregas';


    protected $fillable = [

        'empresa_id',
        'solicitacoes_de_entrega_id',

        'status',
        'tipo_venda',
        'data_prevista',
        'entrega_efetivada_em',
        'proposta',
        'cliente',
        'vendedor_id',
        'modelo',
        'cor',
        'chassi',
        'brinde',
        'foi_solicitado_emplacamento',
        'foi_solicitado_acessorio',
        'acessorios_solicitados',
        'observacoes',

        //Faturamento
        'financiamento_e_seguro',
        'faturamento',

        'segundo_veiculo_na_troca_status',

        //Documentação
        'documentacao_nota_fiscal',
        'documentacao_documentacao_veiculo_com_placa',
        'documentacao_chave_reserva',
        'documentacao_manuais',
        'documentacao_carregador',
        'documentacao_kit_reparo_ou_step',


        //Preparação do veículo
        'preparacao_exterior_revisao_de_entrega',
        'preparacao_exterior_elaboracao_do_comprovante_de_servico',
        'preparacao_exterior_pintura_sem_riscos_e_danos',
        'preparacao_interior_funcionamento_do_veiculo',
        'preparacao_interior_marcador',
        'preparacao_interior_central_multimidia',
        'preparacao_interior_verificacao_de_itens',

        //Serviços estéticos
        'servicos_adicionais_lavagem',
        'servicos_adicionais_combustivel',
        'servicos_adicionais_recarga',


        //Financeiro
        'financeiro_autorizada_pelo_financeiro',
        'financeiro_autorizada_pelo_financeiro_por',
        'financeiro_forma_de_pagamento',
        //'comprovantes_de_pagamento',
        'financeiro_assinatura',


        'cliente_se_recusou_a_receber_as_informacoes',
        'cliente_foi_orientado_a_baixar_o_aplicativo',
        'autorizo_uso_de_minha_imagem_em_publicidade',


        'orientacao_cem_finalizada',
        'orientacao_csi_finalizada',

        'assinatura_do_cliente',
        'aceite_termo',
        'aceita_ativacao_conectividade_carro_conectado',
        'cliente_se_recusou_a_receber_as_informacoes_contidas',

        'byd_acessorios_higienizacao',
        'byd_acessorios_polimento',
        'byd_encantamento_e_instrucao_carro_no_showroom',
        'byd_encantamento_e_instrucao_capa_e_laco',
        'byd_encantamento_e_instrucao_brindes',
        'byd_encantamento_e_instrucao_musica',
        'byd_preparacao_exterior_revisao_de_entrega',
        'byd_preparacao_exterior_elaboracao_do_comprovante_de_servico',
        'byd_preparacao_exterior_pintura_sem_riscos_e_danos',
        'byd_preparacao_interior_funcionamento_do_veiculo',
        'byd_preparacao_interior_marcador',
        'byd_preparacao_interior_central_multimidia',
        'byd_preparacao_interior_verificacao_de_itens',
        'byd_pesquisa_com_7_dias',
        'byd_pesquisa_com_7_dias_finalizada',
        'byd_pesquisa_com_30_dias',
        'byd_pesquisa_com_30_dias_finalizada',

        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $casts = [
        'orientacao_cem' => 'array',
        'orientacao_cem_finalizada' => 'boolean',
        'orientacao_csi' => 'array',
        'orientacao_csi_finalizada' => 'boolean',
        //'comprovantes_de_pagamento' => 'array',
        'acessorios_solicitados' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    public function podeHabilitarAutorizacaoDoFinanceiro(): bool
    {
        $atributos = [
            $this->proposta,
            $this->cliente,
            $this->vendedor,
            $this->modelo,
            $this->cor,
            $this->chassi
        ];

        // Retorna false se algum dos atributos for nulo; caso contrário, retorna true
        return count(array_filter($atributos, fn($atributo) => empty($atributo))) === 0;
    }


    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function financeiroAutorizadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'financeiro_autorizada_pelo_financeiro_por');
    }

    public function segundo_veiculo_na_troca(): HasOne
    {
        return $this->hasOne(SegundoVeiculoNaTroca::class, 'entrega_id');
    }

    public function revisao_de_entrega(): HasOne
    {
        return $this->hasOne(Revisao::class, 'entrega_id');
    }

    public function termo_de_entrega(): HasOne
    {
        return $this->hasOne(TermoDeEntrega::class, 'entrega_id');
    }


    public function acessorios(): HasMany
    {
        return $this->hasMany(Acessorio::class, 'entrega_id', 'id');
    }

    public function notas_da_pesquisa_com_7_dias(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'pesquisa_com_7_dias');
    }

    public function notas_da_pesquisa_com_30_dias(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'pesquisa_com_30_dias');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id', 'id');
    }

    public function notas_da_orientacao_cem(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'orientacao_cem');
    }

    public function notas_da_orientacao_csi(): MorphMany
    {
        return $this->morphMany(Nota::class, 'morphable')->where('grupo', '=', 'orientacao_csi');
    }

    public function fotos(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', '=', 'fotos');
    }

    public function comprovantes_de_pagamento(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', '=', 'comprovantes_de_pagamento');
    }


}
