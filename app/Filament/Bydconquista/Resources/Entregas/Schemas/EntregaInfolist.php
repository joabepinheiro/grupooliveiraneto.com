<?php

namespace App\Filament\Bydconquista\Resources\Entregas\Schemas;

use App\Enums\EntregaStatus;
use App\Models\Entrega;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EntregaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Dados gerais')
                    ->schema([

                        TextEntry::make('tipo_venda')
                            ->label('Tipo de venda')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state)=> EntregaStatus::from($state)->getColor())
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('data_prevista')
                            ->label('Data e horário previsto')
                            ->placeholder('Não informada')
                            ->date('d/m/Y H:i')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('entrega_efetivada_em')
                            ->label('Entrega efetivada em')
                            ->placeholder('Não informada')
                            ->date('d/m/Y H:i')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('proposta')
                            ->label('Proposta')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('cliente')
                            ->label('Cliente')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('vendedor.name')
                            ->label('Vendedor')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('modelo')
                            ->label('Modelo')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('chassi')
                            ->label('Chassi')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('cor')
                            ->label('Cor')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('brinde')
                            ->label('Brinde')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('foi_solicitado_emplacamento')
                            ->label('Foi solicitado emplacamento ?')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Sim' : 'Não')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('foi_solicitado_acessorio')
                            ->label('Foi solicitado acessórios ?')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Sim' : 'Não')
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        RepeatableEntry::make('acessorios_solicitados')
                            ->label('Acessórios solicitados')
                            ->schema([
                                TextEntry::make('acessorio_solicitado')
                                    ->label(''),
                            ])
                            ->placeholder('Nenhum acessório informado')
                            ->contained(false)
                            ->columns(1)
                            ->columnSpanFull(),

                        TextEntry::make('createdBy.name')
                            ->label('Cadastrado por')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('created_at')
                            ->label('Cadastrado em')
                            ->placeholder('Não informada')
                            ->dateTime('d/m/y H:i:s')
                            ->columnSpan([
                                'lg' => 3
                            ]),


                    ])
                    ->columns(12),

                Section::make('Faturamento')
                    ->schema([

                        TextEntry::make('financiamento_e_seguro')
                            ->label('F&i')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('faturamento')
                            ->label('Faturamento')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                    ])->columns(12),

                Section::make('Veículo na troca')
                    ->schema([
                        TextEntry::make('segundo_veiculo_na_troca_status')
                            ->label('2º veículo na troca ?')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Sim' : 'Não')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        Section::make()
                            ->contained(false)
                            ->relationship('segundo_veiculo_na_troca')
                            ->schema([
                                TextEntry::make('nome')
                                    ->label('Nome')
                                    ->placeholder('Não informada')
                                    ->columnSpan([
                                        'lg' =>8
                                    ]),

                                TextEntry::make('chassi')
                                    ->label('Chassi')
                                    ->placeholder('Não informada')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('ano')
                                    ->label('Ano')
                                    ->placeholder('Não informada')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('modelo')
                                    ->label('Modelo')
                                    ->placeholder('Não informada')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('preco')
                                    ->label('Preço')
                                    ->placeholder('Não informada')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('observacao')
                                    ->label('Observações')
                                    ->placeholder('Não informada')
                                    ->columnSpanFull(),
                            ])
                            ->visible(function ($get){
                                return $get('segundo_veiculo_na_troca_status') == 1;
                            })
                            ->columns(12),
                    ])
                    ->columnSpanFull(),

                Section::make('Documentação')
                    ->schema([

                        TextEntry::make('documentacao_nota_fiscal')
                            ->label('Nota fiscal')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_documentacao_veiculo_com_placa')
                            ->label('Documentação / Veículo com placa')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_chave_reserva')
                            ->label('Chave reserva')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_manuais')
                            ->label('Manuais: Proprietário, central multimídia e garantia/revisão')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),


                        TextEntry::make('documentacao_carregador')
                            ->label('Carregador')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_kit_reparo_ou_step')
                            ->label('Kit reparo ou step')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                    ])->columns(12),

                Section::make('Acessórios')
                    ->schema([
                        TextEntry::make('acessorios_higienizacao')
                            ->label('Higienização')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('acessorios_polimento')
                            ->label('Polimento')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),
                    ])->columns(12),


                Section::make('Preparação do veículo (48 horas antes)')
                    ->schema([

                        TextEntry::make('preparacao_exterior_revisao_de_entrega')
                            ->label('Revisão de entrega realizada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_exterior_elaboracao_do_comprovante_de_servico')
                            ->label('Elaboração de comprovante de serviço')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_exterior_pintura_sem_riscos_e_danos')
                            ->label('Pintura, sem riscos e danos')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_funcionamento_do_veiculo')
                            ->label('Funcionamento do veículo')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_marcador')
                            ->label('Marcador')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_central_multimidia')
                            ->label('Central multimídia')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_verificacao_de_itens')
                            ->label('Verificação de itens soltos, conservação e limpeza')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                    ])->columns(12),


                Section::make('Serviços adicionais')
                    ->schema([

                        TextEntry::make('servicos_adicionais_lavagem')
                            ->label('Lavagem')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('servicos_adicionais_recarga')
                            ->label('Recarga')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                    ])->columns(12),


                Section::make('Financeiro')
                    ->schema([

                        TextEntry::make('financeiro_autorizada_pelo_financeiro')
                            ->label(new HtmlString('Autorização do financeiro'))
                            ->badge()
                            ->formatStateUsing(function ($state){
                                if($state == 1){
                                    return 'Autorizada';
                                }else{
                                    return 'Não Autorizada';
                                }
                            })
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextEntry::make('financeiro_forma_de_pagamento')
                            ->label('Forma de pagamento')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextEntry::make('comprovantes_de_pagamento')
                            ->label('Comprovantes de pagamento')
                            ->columnSpanFull()
                            ->placeholder('Não informado')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->formatStateUsing(function ($state) { return sprintf('<span style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"  class="text-xs rounded-md mx-1 font-medium px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30"> <a href="%s"  target="_blank">%s</a></span>', '/storage/'.$state, basename($state)); })
                            ->html(),


                        TextEntry::make('financeiroAutorizadaPor.name')
                            ->label('Autorizado por')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),


                        ImageEntry::make('assinatura')
                            ->label('Assinatura')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                    ])->columns(12),


                Section::make('Encantamento e instrução (12 horas antes)')
                    ->schema([

                        TextEntry::make('encantamento_e_instrucao_carro_no_showroom')
                            ->label('Carro no showroom')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('encantamento_e_instrucao_capa_e_laco')
                            ->label('Capa e laço')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('encantamento_e_instrucao_brindes')
                            ->label('Brindes')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('encantamento_e_instrucao_musica')
                            ->label('Música')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Marcado' : 'Não marcado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                    ])->columns(12),


                Section::make('Fotos')
                    ->schema([
                        RepeatableEntry::make('fotos')
                            ->label('Anexos')
                            ->grid(4)
                            ->hiddenLabel()
                            ->placeholder('Nenhum anexo')
                            ->schema([
                                ImageEntry::make('media')
                                    ->label('Anexo')
                                    ->hiddenLabel()
                                    ->imageWidth('100%')
                                    ->imageHeight('12rem')
                                    ->extraImgAttributes([ 'class' => 'object-cover'])
                                    ->getStateUsing(fn (Media $record) => \App\Models\Media::customPreviewUrl($record))
                                    ->columnSpanFull(),

                                TextEntry::make('created_at')
                                    ->label('Enviado em')
                                    ->dateTime('d/m/y H:i:s')
                                    ->columnSpanFull(),


                                Action::make('download')
                                    ->label('Baixar')
                                    ->icon('fas-download')
                                    ->button()
                                    ->color('gray')
                                    ->extraAttributes(['style' => 'width: 100%'])
                                    ->action(function ($record) {
                                        return response()->download(
                                            $record->getPath(),
                                            $record->file_name
                                        );
                                    }),

                                Action::make('visualizar')
                                    ->label('Visualizar')
                                    ->icon('fas-eye')
                                    ->button()
                                    ->color('gray')
                                    ->extraAttributes(fn ($record) => [
                                        'class' => str_starts_with($record?->mime_type, 'image') ? 'glightbox' : '',
                                        'style' => 'width: 100%'
                                    ])
                                    ->url(function ($record){
                                        return route('private.image', ['path' => $record->getPathRelativeToRoot()]);
                                    }, true),
                            ])->columns(1)
                    ]),

                Section::make('Pesquisa efetuada após 7 dias da entrega')
                    ->schema([
                        RepeatableEntry::make('notas_da_pesquisa_com_7_dias')
                            ->placeholder('Nenhuma anotação')
                            ->label('')
                            ->schema([
                                TextEntry::make('nota')
                                    ->html()
                                    ->label('Anotações')
                                    ->columnSpanFull(),

                                TextEntry::make('createdby.name')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        if(!empty($record)){
                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Pesquisa efetuada após 30 dias da entrega')
                    ->schema([
                        RepeatableEntry::make('notas_da_pesquisa_com_30_dias')
                            ->placeholder('Nenhuma anotação')
                            ->label('')
                            ->schema([
                                TextEntry::make('nota')
                                    ->html()
                                    ->label('Anotações')
                                    ->columnSpanFull(),

                                TextEntry::make('createdby.name')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        if(!empty($record)){
                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Histórico de alterações do status')
                    ->schema([
                        RepeatableEntry::make('activities')
                            ->label('')
                            ->schema([

                                TextEntry::make('created_at')
                                    ->label('Alteração feita em')
                                    ->dateTime('d/m/y H:i:s'),

                                TextEntry::make('causer.name')
                                    ->label('Usuário'),

                                TextEntry::make('properties.attributes.status')
                                    ->badge()
                                    ->color(fn ($state)=> Entrega::colorStatus($state))
                                    ->placeholder('Não informada')
                                    ->label('Status'),

                            ])
                            ->columns(3)
                            ->columnSpanFull()
                    ])

            ]);
    }
}
