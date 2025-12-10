<?php

namespace App\Filament\Movelveiculos\Resources\Entregas\Schemas;

use App\Enums\EntregaStatus;
use App\Filament\Forms\Components\SignaturePad;
use App\Models\Empresa;
use App\Models\Entrega\EntregaHorarioBloqueado;
use App\Models\Modelo;
use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class EntregaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados gerais')
                    ->schema([

                        Select::make('tipo_venda')
                            ->label('Tipo de venda')
                            ->required()
                            ->options([
                                'Venda direta' => 'Venda direta',
                                'Estoque'       => 'Estoque'
                            ])
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        Select::make('status')
                            ->label('Status')
                            ->default('Em andamento')
                            ->required()
                            ->live()
                            ->options(fn ($state)=> EntregaStatus::values())
                            ->prefixIcon(fn ($state)=> EntregaStatus::tryFrom($state)?->getIcon())
                            ->prefixIconColor(fn ($state)=> EntregaStatus::tryFrom($state)?->getColor())
                            ->disableOptionWhen(function ($value, Get $get){
                                if($value == 'Finalizada' && !($get('financeiro_autorizada_pelo_financeiro'))){
                                    return true;
                                }
                                return false;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        DateTimePicker::make('data_prevista')
                            ->label('Data prevista para entrega')
                            ->seconds(false)
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    if (empty($value)) {
                                        return;
                                    }

                                    // Converte o valor recebido (com "T") para Carbon
                                    $data = \Carbon\Carbon::parse($value);

                                    $bloqueado = EntregaHorarioBloqueado::where('start', '<=', $data)
                                        ->where('end', '>=', $data)
                                        ->exists();

                                    if ($bloqueado) {
                                        $fail('A data selecionada está em um horário bloqueado para entregas.');
                                    }
                                };
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        DateTimePicker::make('entrega_efetivada_em')
                            ->label('Entrega efetivada em')
                            ->seconds(false)
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('cliente')
                            ->label('Cliente')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 9
                            ]),

                        TextInput::make('proposta')
                            ->label('Número da proposta')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        Select::make('vendedor_id')
                            ->label('Vendedor')
                            ->required()
                            ->options(User::query()
                                ->with('roles')
                                ->whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'Vendedor');
                                })
                                ->get()
                                ->pluck('name', 'id')
                                ->toArray())
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Select::make('modelo')
                            ->label('Modelo')
                            ->required()
                            ->live()
                            ->options(
                                Modelo::where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                                ->pluck('nome', 'nome')
                            )
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Select::make('cor')
                            ->label('Cor')
                            ->options(function (Get $get) {
                                $modeloNome = $get('modelo');

                                if (! $modeloNome) {
                                    return [];
                                }

                                $modelo = Modelo::where('nome', $modeloNome)->first();

                                if (! $modelo || empty($modelo->cores)) {
                                    return [];
                                }

                                // Se cores for um array de strings
                                return collect($modelo->cores)
                                    ->mapWithKeys(fn ($cor) => [$cor => $cor])
                                    ->toArray();
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('chassi')
                            ->label('Chassi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('brinde')
                            ->label('Brinde')
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Select::make('foi_solicitado_emplacamento')
                            ->label('Foi solicitado emplacamento ?')
                            ->boolean()
                            ->required()
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Select::make('foi_solicitado_acessorio')
                            ->label('Foi solicitado acessórios ?')
                            ->live()
                            ->boolean()
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Repeater::make('acessorios_solicitados')
                            ->label('Acessórios foram solicitados')
                            ->visible(function (Get $get){
                                if($get('foi_solicitado_acessorio')){
                                    return true;
                                }
                                return false;
                            })
                            ->schema([
                                TextInput::make('acessorio_solicitado')
                                    ->hiddenLabel(true)
                                    ->placeholder('Descrição do acessório')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Adicionar acessório solicitado')
                            ->columnSpanFull(),

                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Dados Gerais" do formulário de entrega'))
                    ->columns(12),

                Section::make('Faturamento')
                    ->schema([
                        Toggle::make('financiamento_e_seguro')
                            ->label('F&I')
                            ->columnSpanFull(),

                        Toggle::make('faturamento')
                            ->label('Faturamento')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Faturamento" do formulário de entrega'))
                    ->columns(12),


                Section::make('Veículo na troca')
                    ->schema([
                        Select::make('segundo_veiculo_na_troca_status')
                            ->label('2º veículo na troca ?')
                            ->live()
                            ->options([
                                0 => 'Não',
                                1 => 'Sim',
                            ])
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        Section::make('')
                            ->contained(false)
                            ->relationship('segundo_veiculo_na_troca')
                            ->schema([
                                TextInput::make('nome')
                                    ->label('Nome')
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'lg' => 12
                                    ]),

                                TextInput::make('chassi')
                                    ->label('Chassi')
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'lg' => 3
                                    ]),

                                TextInput::make('ano')
                                    ->label('Ano')
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'lg' => 3
                                    ]),

                                TextInput::make('modelo')
                                    ->label('Modelo')
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'lg' => 3
                                    ]),

                                TextInput::make('preco')
                                    ->label('Preço')
                                    ->prefix('R$')
                                    ->numeric()
                                    ->columnSpan([
                                        'lg' => 3
                                    ]),

                                Textarea::make('observacao')
                                    ->label('Observações')
                                    ->columnSpan([
                                        'lg' => 12
                                    ]),
                            ])
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca_status') == 1;
                            })
                            ->columnSpanFull()
                            ->columns(12),

                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Veículo na troca" do formulário de entrega')),


                Section::make('Documentação')
                    ->schema([
                        Toggle::make('documentacao_nota_fiscal')
                            ->label('Nota fiscal')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Nota fiscal" do formulário de entrega'))
                            ->columnSpanFull(),

                        Toggle::make('documentacao_documentacao_veiculo_com_placa')
                            ->label('Documentação / Veículo com placa')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Documentação / Veículo com placa" do formulário de entrega'))
                            ->columnSpanFull(),

                        Toggle::make('documentacao_chave_reserva')
                            ->label('Chave reserva')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Chave reserva" do formulário de entrega'))
                            ->columnSpanFull(),

                        Toggle::make('documentacao_manuais')
                            ->label('Manuais: Proprietário, central multimídia e garantia/revisão (APP meu VW e/ou impresso)')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Manuais: Proprietário, central multimídia e garantia/revisão (APP meu VW e/ou impresso)" do formulário de entrega'))
                            ->columnSpanFull(),

                    ])
                    ->columns(12),

                Section::make('Fotos')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('fotos')
                            ->label('Fotos')
                            ->collection('fotos')
                            ->previewable()
                            ->downloadable()
                            ->multiple()
                            ->openable()
                            ->disk('local')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Fotos" do formulário de entrega'))
                    ->columns(12),

                Section::make('Revisão de entrega')
                    ->relationship('revisao_de_entrega')
                    ->schema([

                        TextInput::make('numero_da_ordem_de_servico')
                            ->label('Número da ordem de serviço')
                            ->live()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        DatePicker::make('data_da_inspecao')
                            ->label('Data da revisão')
                            ->live()
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('nome_do_responsavel_pela_inspecao')
                            ->label('Nome do responsável pela revisão')
                            ->maxLength(255)
                            ->live()
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        Fieldset::make('Preparação do veículo')
                            ->schema([
                                Toggle::make('preparacao_exterior_revisao_de_entrega')
                                    ->label('Revisão de entrega realizada conforme tabela de manutenção ELSA')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_exterior_elaboracao_do_comprovante_de_servico')
                                    ->label('Elaboração de comprovante de serviço')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_exterior_pintura_sem_riscos_e_danos')
                                    ->label('Pintura, sem riscos e danos')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_interior_funcionamento_do_veiculo')
                                    ->label('Funcionamento do veículo')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_interior_marcador')
                                    ->label('Marcador')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_interior_central_multimidia')
                                    ->label('Central multimídia')
                                    ->columnSpanFull(),

                                Toggle::make('preparacao_interior_verificacao_de_itens')
                                    ->label('Verificação de itens soltos, conservação e limpeza')
                                    ->columnSpanFull(),


                            ])
                            ->columnSpanFull(),

                        SignaturePad::make('assinatura_inspecao')
                            ->label(__('Assinatura do mecânico'))
                            ->extraAttributes(['class' => 'border-4 border-indigo-500/100'])
                            ->dotSize(2.0)
                            ->lineMinWidth(0.5)
                            ->lineMaxWidth(2.5)
                            ->throttle(16)
                            ->minDistance(5)
                            ->velocityFilterWeight(0.7)
                            ->columnSpanFull(),

                        ToggleButtons::make('veiculo_liberado')
                            ->label('Veículo liberado ?')
                            ->boolean()
                            ->default(0)
                            ->inline()
                            ->disabled(function (Get $get){
                                if(
                                    empty($get('numero_da_ordem_de_servico')) ||
                                    empty($get('data_da_inspecao')) ||
                                    empty($get('nome_do_responsavel_pela_inspecao')) ||
                                    empty($get('assinatura_inspecao'))
                                ){
                                    return true;
                                }

                                return  false;
                            })
                            ->helperText('O botão de confirmação para a liberação do veículo só poderá ser ativado como "Sim" após o preenchimento completo das informações referentes à revisão de entrega.')
                            ->columnSpanFull(),

                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Revisão de entrega" do formulário de entrega'))
                    ->columnSpanFull()
                    ->columns(12),

                Section::make('Acessórios')
                    ->schema([
                        Repeater::make('acessorios')
                            ->relationship('acessorios')
                            ->hiddenLabel(true)
                            ->schema([
                                TextInput::make('descricao')
                                    ->label('Descrição do acessório')
                                    ->placeholder('Descrição do acessório')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'lg' => 9
                                    ]),

                                ToggleButtons::make('instalado')
                                    ->label('O acessório já foi instalado?')
                                    ->required()
                                    ->boolean()
                                    ->inline()
                                    ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "O acessório já foi instalado?" do formulário de entrega'))
                                    ->columnSpan([
                                        'lg' =>3
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->minItems(0)
                            ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Acessórios" do formulário de entrega'))
                            ->addActionLabel('Adicionar outro acessório')
                            ->columns(12),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),


                Section::make('Serviços estéticos')
                    ->schema([
                        Toggle::make('servicos_adicionais_lavagem')
                            ->label('Lavagem')
                            ->columnSpanFull(),

                        Toggle::make('servicos_adicionais_combustivel')
                            ->label('Combustível')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Serviços estéticos" do formulário de entrega'))
                    ->columns(12),

                Section::make('Financeiro')
                    ->schema([

                        TextInput::make('financeiro_forma_de_pagamento')
                            ->label('Forma de pagamento')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Forma de pagamento" do formulário de entrega'))
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        SpatieMediaLibraryFileUpload::make('comprovantes_de_pagamento')
                            ->label('Comprovantes de pagamento')
                            ->helperText('Somente o gerente de vendas e a secretária de vendas podem anexar os comprovantes')
                            ->collection('comprovantes_de_pagamento')
                            ->previewable()
                            ->downloadable()
                            ->multiple()
                            ->openable()
                            ->columnSpanFull()
                            ->disk('local')
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Comprovantes de pagamento" do formulário de entrega')),

                        Hidden::make('financeiro_autorizada_pelo_financeiro_por'),

                        Toggle::make('financeiro_autorizada_pelo_financeiro')
                            ->extraFieldWrapperAttributes(['class' => 'py-5'])
                            ->label(function ($state, Get $get){
                                if($state){
                                    if(User::find($get('financeiro_autorizada_pelo_financeiro_por'))){
                                        return  'Autorizado por '. User::find($get('financeiro_autorizada_pelo_financeiro_por'))->name;
                                    }

                                    return  'Autorizado pelo financeiro ';
                                }else{
                                    return 'Não autorizado pelo gerente financeiro';
                                }
                            })
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Autorizado pelo financeiro" do formulário de entrega'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state){
                                if($state){
                                    $set('financeiro_autorizada_pelo_financeiro_por', auth()->user()->id);
                                }else{
                                    $set('financeiro_autorizada_pelo_financeiro_por', null);
                                }
                            })
                            ->columnSpanFull(),

                        SignaturePad::make('financeiro_assinatura')
                            ->label(__('Assinatura do gerente financeiro'))
                            ->view('filament.forms.components.signature-pad')
                            ->dotSize(2.0)
                            ->lineMinWidth(0.5)
                            ->lineMaxWidth(2.5)
                            ->throttle(16)
                            ->minDistance(1)
                            ->velocityFilterWeight(0.7)
                            ->columnSpanFull()
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Assinatura do gerente financeiro" do formulário de entrega')),

                    ])
                    ->columns(12),

                Section::make('Termo de entrega e aceite de veículo')
                    ->relationship('termo_de_entrega')
                    ->schema([

                        Fieldset::make('Itens obrigatórios e de segurança')
                            ->schema([
                                ToggleButtons::make('teav_presenca_do_estepe_chave_macado_triangulo')
                                    ->label('Presença do estepe, chave de roda, macaco e triângulo de segurança')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_recomendar_leitura_manual_seguranca')
                                    ->label('Recomendar expressamente a leitura do capítulo de segurança no manual do proprietário')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Acessórios e serviços de estética')
                            ->schema([
                                ToggleButtons::make('teav_acessorios_instalados')
                                    ->label('Acessórios instalados')
                                    ->helperText('A instalação de acessórios não originais pode interferir no funcionamento e segurança do veículo, além de comprometer a garantia não é permitida instalação de banco em couro no veículo com airbaigs laterais')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_servicos_de_estetica')
                                    ->label('Serviços de estética realizados')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Interior')
                            ->schema([
                                ToggleButtons::make('teav_funcionamento_do_painel_computador_de_bordo_sistema')
                                    ->label('Funcionamento do painel, computador de bordo, sistema de som ou central multimídia ( ex: conectividade, câmera de ré, gps, sd card, etc.)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),


                                ToggleButtons::make('teav_app_meu_vw_demonstracao_de_funcionalidades_indica')
                                    ->label('App meu vw demonstração de funcionalidades, indicação de concessiónaria favorita e, se necessário, auxílio na instalação configuração')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_do_sistema_de_ar_condicionado')
                                    ->label('Operação do sistema de ar-condicionado: manual e/ou automático (desembaçamento dos vidros)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_funcionamento_do_espelho_convexos_travas')
                                    ->label('Funcionamento do espelho convexos, travas, vidros elétricos, com função tilt down e alarme')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro')
                                    ->label('Operação do sistema de controle de velocidade de cruzeiro')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_do_limpador_de_parabrisa')
                                    ->label('Operação do limpador de parabrisa (ex: temporizador e sensor de chuva)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_funcionamento_do_acendimento_automatico_dos_far')
                                    ->label('Funcionamento do acendimento automático dos faróis e luzes de conversão')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_dos_ajustes_e_comandos_dos_bancos')
                                    ->label('Operação dos ajustes e comandos dos bancos')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_dos_ajustes_e_da_coluna_de_direcao')
                                    ->label('Operação dos ajustes e da coluna de direção')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_operacao_de_abertura_do_porta_malas_e_do_bocal')
                                    ->label('Operação de abertura do porta-malas e do bocal')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_funcionamento_do_sistema_flex')
                                    ->label('Funcionamento do sistema flex (5km)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10')
                                    ->label('Se motor a diesel orientar quanto ao uso diesel s10 e aditivos/estabilizadores recomendados')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Documentação e garantia')
                            ->schema([
                                ToggleButtons::make('teav_nota_fiscal')
                                    ->label('Nota fiscal')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_chave_reserva')
                                    ->label('Chave reserva')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_documentacao_veiculo_com_placa')
                                    ->label('Documentação/veículo com placa')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_manuais_proprietario_central_multimidia_e_garantia')
                                    ->label('Manuais: proprietário, central multimidía e garantia/revisão (app meu vw e/ou impresso)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_tempo_de_garantia_e_plano_de_manutencao')
                                    ->label('Tempo de garantia e plano de manutenção( a cada 10.000km ou 12 meses)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo')
                                    ->label('Em condição severa, orientação de uso, de trocas de óleo e de manutenção')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_em_condicoes_de_cobertura_e_itens_nao_cobertos')
                                    ->label('Em condições de cobertura e itens não cobertos pela garantia.itens de desgaste, bateria, uso severo.')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_sistema_de_agendamento_e_apresentacao_do_pos_venda')
                                    ->label('Sistema de agendamento e apresentação do pós venda (nec. aprofundamento)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_volkswagen_service_24_horas')
                                    ->label('Volkswagen service 24 horas ( número,acesso via vw play e condições gerais)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Exterior')
                            ->schema([

                                ToggleButtons::make('teav_compartimento_do_motor_reservatorios_e_fluidos')
                                    ->label('Compartimento do motor (reservatórios e fluídos)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_presenca_de_antena_inclusive_quando_nao')
                                    ->label('Presença de antena ( inclusive quando não for visível)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_sistema_kessy_chave_presencial')
                                    ->label('Sistema kessy-chave presencial')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),

                                ToggleButtons::make('teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo')
                                    ->label('Uso da "lâmina" da chave para acesso ao veículo (chave sem bateria)')
                                    ->options([
                                        'Orientado'             =>'Orientado',
                                        'Entregue/Demonstrado'  => 'Entregue/Demonstrado',
                                        'Não se aplica'         => 'Não se aplica',
                                    ])
                                    ->extraFieldWrapperAttributes(['class' => 'py-2'])
                                    ->inline()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Termo de entrega e aceite de veículo" do formulário de entrega')),

                Section::make('Observações e termos de aceite')
                    ->schema([

                        RichEditor::make('observacoes')
                            ->label('Observações')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        Toggle::make('cliente_se_recusou_a_receber_as_informacoes')
                            ->label('Cliente se recusou a receber as informações contidas neste formulário')
                            ->helperText('Importante: Não havendo observação, será considerado que o cliente está de acordo com o recebimento do veículo.')
                            ->columnSpanFull(),

                        Toggle::make('cliente_foi_orientado_a_baixar_o_aplicativo')
                            ->label('O cliente foi orientado a baixar o aplicativo do Meu Volkswagen, onde foi explicado como tirar boletos do banco Volkswagen e demais funções do aplicativo.')
                            ->columnSpanFull(),

                        Toggle::make('autorizo_uso_de_minha_imagem_em_publicidade')
                            ->label('Uso de imagem/vídeo e tratamento de dados')
                            ->helperText('AUTORIZO a utilização de minha imagem e/ou vídeo, integralmente ou em parte, desde a presente data, em caráter gratuito, para utilização em trabalhos de publicidade e/ou divulgação comercial (mídias eletrônicas). Por ser esta a expressão de minha vontade, nada terei a reclamar a título de direitos conexos à minha imagem ou qualquer outro. Declaro ainda que estou ciente e concordo com o tratamento de meus dados de acordo com a Política de Privacidade da concessionária supracitada.')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Observações e termos de aceite" do formulário de entrega')),


                Section::make('Orientação CEM')
                    ->schema([

                        Repeater::make('notas_da_orientacao_cem')
                            ->hiddenLabel(true)
                            ->relationship('notas_da_orientacao_cem',  modifyQueryUsing: fn (Builder $query) => $query->where('grupo', '=', 'orientacao_cem'))
                            ->schema([

                                Hidden::make('grupo')
                                    ->default('orientacao_cem'),

                                TextInput::make('atributos.ligacao_realizada_por')
                                    ->label('Ligação realizada por')
                                    ->columnSpan([
                                        'lg' => 8
                                    ]),

                                DatePicker::make('atributos.data_da_ligacao')
                                    ->label('Data da ligação')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                Textarea::make('nota')
                                    ->label('Anotações')
                                    ->rows(8)
                                    ->columnSpanFull(),

                                TextEntry::make('created_by')
                                    ->hiddenLabel(true)
                                    ->state(function ($record) {
                                        if(!empty($record)){

                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),

                            ])
                            ->addActionLabel('Adicionar anotação')
                            ->columnSpanFull()
                            ->columns(12),

                        Toggle::make('orientacao_cem_finalizada')
                            ->label('Orientação CSI finalizada')
                            ->columnSpanFull(),

                    ])
                    ->visible(function ($record, $operation){
                        if($operation == 'edit'){
                            if(Permission::can('Entrega - Alterar a seção "Orientação CEM" do formulário de entrega') && $record->status == 'Finalizada'){
                                return true;
                            }
                        }

                        return false;
                    }),

                Section::make('Orientação CSI')
                    ->schema([

                        Repeater::make('notas_da_orientacao_csi')
                            ->hiddenLabel(true)
                            ->relationship('notas_da_orientacao_csi',  modifyQueryUsing: fn (Builder $query) => $query->where('grupo', '=', 'orientacao_csi'))
                            ->schema([

                                Hidden::make('grupo')
                                    ->default('orientacao_csi'),

                                TextInput::make('atributos.ligacao_realizada_por')
                                    ->label('Ligação realizada por')
                                    ->columnSpan([
                                        'lg' => 8
                                    ]),

                                DatePicker::make('atributos.data_da_ligacao')
                                    ->label('Data da ligação')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                Textarea::make('nota')
                                    ->label('Anotações')
                                    ->rows(8)
                                    ->columnSpanFull(),

                                TextEntry::make('created_by')
                                    ->hiddenLabel(true)
                                    ->state(function ($record) {
                                        if(!empty($record)){

                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),

                            ])
                            ->addActionLabel('Adicionar anotação')
                            ->columnSpanFull()
                            ->columns(12),

                        Toggle::make('orientacao_csi_finalizada')
                            ->label('Orientação CSI finalizada')
                            ->columnSpanFull(),

                    ])
                    ->visible(function ($record, $operation){

                        if($operation == 'edit'){
                            if(Permission::can('Entrega - Alterar a seção "Orientação CSI" do formulário de entrega') && $record->status == 'Finalizada'){
                                return true;
                            }
                        }

                        return false;
                    }),
            ]);
    }
}
