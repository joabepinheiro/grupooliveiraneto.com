<?php

namespace App\Filament\Bydconquista\Resources\Entregas\Schemas;

use App\Enums\EntregaStatus;
use App\Filament\Forms\Components\SignaturePad;
use App\Models\Entrega;
use App\Models\Entrega\EntregaHorarioBloqueado;
use App\Models\Modelo;
use App\Models\Permission;
use App\Models\User;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;


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
                                'Venda direta'  => 'Venda direta',
                                'Estoque'       => 'Estoque'
                            ])
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->live()
                            ->options(EntregaStatus::values())
                            ->prefixIcon(fn ($state) => EntregaStatus::tryFrom($state)?->getIcon())
                            ->prefixIconColor(fn ($state) => EntregaStatus::tryFrom($state)?->getColor())
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
                            ->options(Modelo::all()->pluck('nome', 'nome'))
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

                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Dados Gerais" do formulário de entrega'))
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
                                    if($get('venda_direta') == 1){
                                        return 'Não autorizado pelo gerente financeiro ou secretária de vendas';
                                    }
                                    return 'Não autorizado pelo gerente financeiro';

                                }
                            })
                            ->helperText(function ($record, $operation){
                                if ($operation === 'edit') {
                                    if (!$record->podeHabilitarAutorizacaoDoFinanceiro()) {
                                        return 'A autorização só poderá ser executada caso os campos "Número da Proposta," "Cliente," "Vendedor," "Modelo," "Cor," e "Chassi" estejam preenchidos';
                                    }
                                }

                                return  '';
                            })
                            ->disabled(function ($record, $operation, Get $get) {
                                // Desabilita se a operação for de edição e 'cliente' ou 'proposta' estiverem nulos
                                if ($operation === 'edit') {
                                    if (!$record->podeHabilitarAutorizacaoDoFinanceiro()) {
                                        return true;
                                    }
                                }

                                if($get('venda_direta') == 1){
                                    if (auth()->user()->roles->contains('name', 'Gerente financeiro') || auth()->user()->roles->contains('name', 'Secretária de vendas')) {
                                        return false;
                                    }
                                }else{
                                    // Permite habilitação se o usuário tiver a role 'Gerente financeiro'
                                    if (auth()->user()->roles->contains('name', 'Gerente financeiro')) {
                                        return false;
                                    }
                                }

                                // Desabilita para todos os demais casos
                                return true;
                            })
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
                            ->dotSize(2.0)
                            ->lineMinWidth(0.5)
                            ->lineMaxWidth(2.5)
                            ->throttle(16)
                            ->minDistance(5)
                            ->velocityFilterWeight(0.7)
                            ->columnSpanFull()
                            ->disabled(fn() => !Permission::can('Entrega - Alterar o campo "Assinatura do gerente financeiro" do formulário de entrega')),

                    ])
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

                        Select::make('segundo_veiculo_na_troca')
                            ->label('2º veículo na troca ?')
                            ->live()
                            ->options([
                                0 => 'Não',
                                1 => 'Sim',
                            ])
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextInput::make('segundo_veiculo_na_troca_nome')
                            ->label('Nome')
                            ->maxLength(255)
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextInput::make('segundo_veiculo_na_troca_chassi')
                            ->label('Chassi')
                            ->maxLength(255)
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('segundo_veiculo_na_troca_ano')
                            ->label('Ano')
                            ->maxLength(255)
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('segundo_veiculo_na_troca_modelo')
                            ->label('Modelo')
                            ->maxLength(255)
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('segundo_veiculo_na_troca_preco')
                            ->label('Preço')
                            ->prefix('R$')
                            ->numeric()
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        Textarea::make('segundo_veiculo_na_troca_observacao')
                            ->label('Observações')
                            ->visible(function (Get $get){
                                return $get('segundo_veiculo_na_troca') == 1;
                            })
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Veículo na troca" do formulário de entrega'))
                    ->columns(12),

                Section::make('Documentação')
                    ->schema([
                        Toggle::make('documentacao_nota_fiscal')
                            ->label('Nota fiscal')
                            ->columnSpanFull(),

                        Toggle::make('documentacao_documentacao_veiculo_com_placa')
                            ->label('Documentação / Veículo com placa')
                            ->columnSpanFull(),

                        Toggle::make('documentacao_chave_reserva')
                            ->label('Chave reserva')
                            ->columnSpanFull(),

                        Toggle::make('documentacao_manuais')
                            ->label('Manuais: Proprietário, central multimídia e garantia/revisão')
                            ->columnSpanFull(),

                        Toggle::make('documentacao_carregador')
                            ->label('Carregador')
                            ->columnSpanFull(),

                        Toggle::make('documentacao_kit_reparo_ou_step')
                            ->label('Kit reparo ou step')
                            ->columnSpanFull(),
                    ])
                    ->columns(12),


                Section::make('Acessórios')
                    ->schema([
                        Toggle::make('byd_acessorios_higienizacao')
                            ->label('Higienização')
                            ->columnSpanFull(),

                        Toggle::make('byd_acessorios_polimento')
                            ->label('Polimento')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Acessórios" do formulário de entrega'))
                    ->columns(12),

                Section::make('Preparação do veículo (48 horas antes)')
                    ->schema([
                        Toggle::make('byd_preparacao_exterior_revisao_de_entrega')
                            ->label('Revisão de entrega realizada')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_exterior_elaboracao_do_comprovante_de_servico')
                            ->label('Elaboração de comprovante de serviço')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_exterior_pintura_sem_riscos_e_danos')
                            ->label('Pintura, sem riscos e danos')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_interior_funcionamento_do_veiculo')
                            ->label('Funcionamento do veículo')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_interior_marcador')
                            ->label('Marcador')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_interior_central_multimidia')
                            ->label('Central multimídia')
                            ->columnSpanFull(),

                        Toggle::make('byd_preparacao_interior_verificacao_de_itens')
                            ->label('Verificação de itens soltos, conservação e limpeza')
                            ->columnSpanFull(),


                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Preparação do veículo (48 horas antes)" do formulário de entrega'))
                    ->columns(12),

                Section::make('Serviços adicionais')
                    ->schema([
                        Toggle::make('servicos_adicionais_lavagem')
                            ->label('Lavagem')
                            ->columnSpanFull(),

                        Toggle::make('servicos_adicionais_recarga')
                            ->label('Recarga')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Serviços adicionais" do formulário de entrega'))
                    ->columns(12),


                Section::make('Encantamento e instrução (12 horas antes)')
                    ->schema([

                        Toggle::make('byd_encantamento_e_instrucao_carro_no_showroom')
                            ->label('Carro no showroom')
                            ->columnSpanFull(),

                        Toggle::make('byd_encantamento_e_instrucao_capa_e_laco')
                            ->label('Capa e laço')
                            ->columnSpanFull(),

                        Toggle::make('byd_encantamento_e_instrucao_brindes')
                            ->label('Brindes')
                            ->columnSpanFull(),

                        Toggle::make('byd_encantamento_e_instrucao_musica')
                            ->label('Música')
                            ->columnSpanFull(),

                        SpatieMediaLibraryFileUpload::make('fotos')
                            ->label('Fotos')
                            ->collection('entregas')
                            ->previewable()
                            ->downloadable()
                            ->multiple()
                            ->openable()
                            ->disk('local')
                            ->columnSpanFull(),
                    ])
                    ->disabled(fn() => !Permission::can('Entrega - Alterar a seção "Encantamento e instrução (12 horas antes)" do formulário de entrega'))
                    ->columns(12),



                Section::make('Pesquisa efetuada após 7 dias da entrega')
                    ->schema([

                        Repeater::make('notas_da_pesquisa_com_7_dias')
                            ->hiddenLabel(true)
                            ->relationship('notas_da_pesquisa_com_7_dias', modifyQueryUsing: fn (Builder $query) => $query->where('grupo', '=', 'pesquisa_com_7_dias'))
                            ->schema([

                                Hidden::make('grupo')
                                    ->default('pesquisa_com_7_dias'),

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
                            ->columns(1),

                        Toggle::make('byd_pesquisa_com_7_dias_finalizada')
                            ->label('Pesquisa de 7 dias finalizada')
                            ->columnSpanFull(),

                    ])
                    ->visible(function ($record, $operation){
                        if($operation == 'edit'){
                            return Permission::can('Entrega - Visualisar a seção "Pesquisa efetuada após 7 dias da entrega" do formulário de entrega');
                        }
                        return false;
                    }),

                Section::make('Pesquisa efetuada após 30 dias da entrega')
                    ->schema([

                        Repeater::make('notas_da_pesquisa_com_30_dias')
                            ->hiddenLabel(true)
                            ->relationship('notas_da_pesquisa_com_30_dias',  modifyQueryUsing: fn (Builder $query) => $query->where('grupo', '=', 'pesquisa_com_30_dias'))
                            ->schema([

                                Hidden::make('grupo')
                                    ->default('pesquisa_com_30_dias'),

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
                            ->columns(1),

                        Toggle::make('byd_pesquisa_com_30_dias_finalizada')
                            ->label('Pesquisa de 30 dias finalizada')
                            ->columnSpanFull(),

                    ])
                    ->visible(function ($record, $operation){
                        if($operation == 'edit'){
                            return Permission::can('Entrega - Visualisar a seção "Pesquisa efetuada após 30 dias da entrega" do formulário de entrega');
                        }
                        return false;
                    }),


            ]);
    }
}
