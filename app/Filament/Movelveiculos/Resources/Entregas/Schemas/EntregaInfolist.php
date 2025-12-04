<?php

namespace App\Filament\Movelveiculos\Resources\Entregas\Schemas;

use App\Enums\ActivityLogEvent;
use App\Enums\EntregaStatus;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
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
                                    ->hiddenLabel()
                                    ->hiddenLabel(true),
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
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('faturamento')
                            ->label('Faturamento')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
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
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_documentacao_veiculo_com_placa')
                            ->label('Documentação / Veículo com placa')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_chave_reserva')
                            ->label('Chave reserva')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('documentacao_manuais')
                            ->label('Manuais: Proprietário, central multimídia e garantia/revisão')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
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


                Section::make('Revisão de entrega')
                    ->relationship('revisao_de_entrega')
                    ->schema([
                        TextEntry::make('veiculo_liberado')
                            ->label('Veículo liberado ?')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
                            ->columnSpan([
                                'lg' => 12
                            ]),


                        TextEntry::make('data_da_inspecao')
                            ->label('Data da inspeção')
                            ->placeholder('Não informada')
                            ->date('d/m/Y')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('nome_do_responsavel_pela_inspecao')
                            ->label('Nome do responsável pela inspeção')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 9
                            ]),

                        TextEntry::make('preparacao_exterior_revisao_de_entrega')
                            ->label('Revisão de entrega realizada conforme tabela de manutenção ELSA')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_exterior_elaboracao_do_comprovante_de_servico')
                            ->label('Elaboração de comprovante de serviço')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_exterior_pintura_sem_riscos_e_danos')
                            ->label('Pintura, sem riscos e danos')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_funcionamento_do_veiculo')
                            ->label('Funcionamento do veículo')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_marcador')
                            ->label('Marcador')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_central_multimidia')
                            ->label('Central multimídia')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('preparacao_interior_verificacao_de_itens')
                            ->label('Verificação de itens soltos, conservação e limpeza')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        ImageEntry::make('assinatura_inspecao')
                            ->label('Assinatura')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ])->columns(12),

                Section::make('Acessórios')
                    ->schema([
                        RepeatableEntry::make('acessorios')
                            ->placeholder('Nenhuma anotação')
                            ->hiddenLabel(true)
                            ->schema([

                                TextEntry::make('descricao')
                                    ->html()
                                    ->label('Descrição do acessório')
                                    ->columnSpan([
                                        'lg' => 8
                                    ]),

                                TextEntry::make('instalado')
                                    ->html()
                                    ->label('O acessório já foi instalado?')
                                    ->badge()
                                    ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                                    ->formatStateUsing(fn($state) => $state == 1 ? 'Instalado' : 'Não instalado')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),
                            ])
                            ->columns(12)
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
                    ->columns(1),


                Section::make('Serviços estéticos')
                    ->schema([

                        TextEntry::make('servicos_adicionais_lavagem')
                            ->label('Lavagem')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizada' : 'Não realizado')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('servicos_adicionais_combustivel')
                            ->label('Combustível')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Realizado' : 'Não realizado')
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

                        TextEntry::make('financeiroAutorizadaPor.name')
                            ->label('Autorizado por')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),


                        ImageEntry::make('financeiro_assinatura')
                            ->label('Assinatura do financeiro')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),



                    ])->columns(12),

                Section::make('Comprovantes de pagamento')
                    ->schema([
                        RepeatableEntry::make('comprovantes_de_pagamento')
                            ->label('Comprovantes de pagamento')
                            ->hiddenLabel()
                            ->grid(4)
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

                                TextEntry::make('name')
                                    ->label('Nome do comprovante')
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
                            ])
                            ->columnSpanFull()
                            ->columns(1),
                    ]),


                Section::make('Termo de entrega e aceite de veículo')
                    ->relationship('termo_de_entrega')
                    ->schema([

                        Fieldset::make('Itens obrigatórios e de segurança')
                            ->schema([
                                TextEntry::make('teav_presenca_do_estepe_chave_macado_triangulo')
                                    ->label('Presença do estepe, chave de roda, macaco e triângulo de segurança')
                                    ->placeholder('Não informado')
                                    ->badge()
                                    ->columnSpanFull(),

                                TextEntry::make('teav_recomendar_leitura_manual_seguranca')
                                    ->label('Recomendar expressamente a leitura do capítulo de segurança no manual do proprietário')
                                    ->placeholder('Não informado')
                                    ->badge()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Acessórios e serviços de estética')
                            ->schema([
                                TextEntry::make('teav_acessorios_instalados')
                                    ->label('Acessórios instalados')
                                    ->placeholder('Não informado')
                                    ->badge()
                                    ->columnSpanFull(),

                                TextEntry::make('teav_servicos_de_estetica')
                                    ->label('Serviços de estética realizados')
                                    ->placeholder('Não informado')
                                    ->badge()
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Interior')
                            ->schema([
                                TextEntry::make('teav_funcionamento_do_painel_computador_de_bordo_sistema')
                                    ->label('Funcionamento do painel, computador de bordo, sistema de som ou central multimídia ( ex: conectividade, câmera de ré, gps, sd card, etc.)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),


                                TextEntry::make('teav_app_meu_vw_demonstracao_de_funcionalidades_indica')
                                    ->label('App meu vw demonstração de funcionalidades, indicação de concessiónaria favorita e, se necessário, auxílio na instalação configuração')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_do_sistema_de_ar_condicionado')
                                    ->label('Operação do sistema de ar-condicionado: manual e/ou automático (desembaçamento dos vidros)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_funcionamento_do_espelho_convexos_travas')
                                    ->label('Funcionamento do espelho convexos, travas, vidros elétricos, com função tilt down e alarme')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_do_sistema_de_controle_de_velocidade_de_cruzeiro')
                                    ->label('Operação do sistema de controle de velocidade de cruzeiro')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_do_limpador_de_parabrisa')
                                    ->label('Operação do limpador de parabrisa (ex: temporizador e sensor de chuva)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_funcionamento_do_acendimento_automatico_dos_far')
                                    ->label('Funcionamento do acendimento automático dos faróis e luzes de conversão')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_dos_ajustes_e_comandos_dos_bancos')
                                    ->label('Operação dos ajustes e comandos dos bancos')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_dos_ajustes_e_da_coluna_de_direcao')
                                    ->label('Operação dos ajustes e da coluna de direção')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_operacao_de_abertura_do_porta_malas_e_do_bocal')
                                    ->label('Operação de abertura do porta-malas e do bocal')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_funcionamento_do_sistema_flex')
                                    ->label('Funcionamento do sistema flex (5km)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_se_motor_a_diesel_orientar_quanto_ao_uso_diesel_s10')
                                    ->label('Se motor a diesel orientar quanto ao uso diesel s10 e aditivos/estabilizadores recomendados')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Documentação e garantia')
                            ->schema([
                                TextEntry::make('teav_nota_fiscal')
                                    ->label('Nota fiscal')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_chave_reserva')
                                    ->label('Chave reserva')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_documentacao_veiculo_com_placa')
                                    ->label('Documentação/veículo com placa')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_manuais_proprietario_central_multimidia_e_garantia')
                                    ->label('Manuais: proprietário, central multimidía e garantia/revisão (app meu vw e/ou impresso)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_tempo_de_garantia_e_plano_de_manutencao')
                                    ->label('Tempo de garantia e plano de manutenção( a cada 10.000km ou 12 meses)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_em_condicao_severa_orientacao_de_uso_de_trocas_de_oleo')
                                    ->label('Em condição severa, orientação de uso, de trocas de óleo e de manutenção')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_em_condicoes_de_cobertura_e_itens_nao_cobertos')
                                    ->label('Em condições de cobertura e itens não cobertos pela garantia.itens de desgaste, bateria, uso severo.')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_sistema_de_agendamento_e_apresentacao_do_pos_venda')
                                    ->label('Sistema de agendamento e apresentação do pós venda (nec. aprofundamento)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_volkswagen_service_24_horas')
                                    ->label('Volkswagen service 24 horas ( número,acesso via vw play e condições gerais)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Exterior')
                            ->schema([

                                TextEntry::make('teav_compartimento_do_motor_reservatorios_e_fluidos')
                                    ->label('Compartimento do motor (reservatórios e fluídos)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_presenca_de_antena_inclusive_quando_nao')
                                    ->label('Presença de antena ( inclusive quando não for visível)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_sistema_kessy_chave_presencial')
                                    ->label('Sistema kessy-chave presencial')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),

                                TextEntry::make('teav_uso_da_lamina_da_chave_para_acesso_ao_veiculo')
                                    ->label('Uso da "lâmina" da chave para acesso ao veículo (chave sem bateria)')
                                    ->badge()
                                    ->placeholder('Não informado')
                                    ->columnSpanFull(),
                            ]),
                    ]),


                Section::make('')
                    ->schema([

                        TextEntry::make('cliente_se_recusou_a_receber_as_informacoes')
                            ->label('Cliente se recusou a receber as informações contidas neste formulário')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Sim' : 'Não')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextEntry::make('cliente_foi_orientado_a_baixar_o_aplicativo')
                            ->label('O cliente foi orientado a baixar o aplicativo do Meu Volkswagen, onde foi explicado como tirar boletos do banco Volkswagen e demais funções do aplicativo.')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Sim' : 'Não')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TextEntry::make('autorizo_uso_de_minha_imagem_em_publicidade')
                            ->label('Uso de imagem/vídeo e tratamento de dados: AUTORIZO a utilização de minha imagem e/ou vídeo, integralmente ou em parte, desde a presente data, em caráter gratuito, para utilização em trabalhos de publicidade e/ou divulgação comercial (mídias eletrônicas). Por ser esta a expressão de minha vontade, nada terei a reclamar a título de direitos conexos à minha imagem ou qualquer outro. Declaro ainda que estou ciente e concordo com o tratamento de meus dados de acordo com a Política de Privacidade da concessionária supracitada.')
                            ->placeholder('Não informada')
                            ->badge()
                            ->color(fn($state) => $state == 1 ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state == 1 ? 'Autorizado' : 'Não autorizado')
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ]),


                Section::make('Assinatura do cliente')
                    ->schema([
                        ImageEntry::make('assinatura_do_cliente')
                            ->hiddenLabel(true)
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),

                    ]),

                Section::make('Notas da orientação CEM')
                    ->schema([
                        RepeatableEntry::make('notas_da_orientacao_cem')
                            ->placeholder('Nenhuma anotação')
                            ->hiddenLabel(true)
                            ->schema([

                                TextEntry::make('atributos.ligacao_realizada_por')
                                    ->html()
                                    ->label('Ligação realizada por')
                                    ->columnSpan([
                                        'lg' => 8
                                    ]),

                                TextEntry::make('atributos.data_da_ligacao')
                                    ->html()
                                    ->label('Data da ligação')
                                    ->date('d/m/Y')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('nota')
                                    ->html()
                                    ->label('Anotações')
                                    ->columnSpanFull(),

                                TextEntry::make('createdby.name')
                                    ->hiddenLabel(true)
                                    ->formatStateUsing(function ($record) {
                                        if(!empty($record)){
                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(12)
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
                    ->columns(1),

                Section::make('Notas da orientação CSI')
                    ->schema([
                        RepeatableEntry::make('notas_da_orientacao_csi')
                            ->placeholder('Nenhuma anotação')
                            ->hiddenLabel(true)
                            ->schema([
                                TextEntry::make('atributos.ligacao_realizada_por')
                                    ->html()
                                    ->label('Ligação realizada por')
                                    ->columnSpan([
                                        'lg' => 8
                                    ]),

                                TextEntry::make('atributos.data_da_ligacao')
                                    ->html()
                                    ->label('Data da ligação')
                                    ->date('d/m/Y')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('nota')
                                    ->html()
                                    ->label('Anotações')
                                    ->columnSpanFull(),

                                TextEntry::make('createdby.name')
                                    ->hiddenLabel(true)
                                    ->formatStateUsing(function ($record) {
                                        if(!empty($record)){
                                            return (new HtmlString('Por <span class="font-semibold">'.$record->createdby->name. '</span> em '. \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i')));
                                        }
                                        return '';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(12)
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
                    ->columns(1),
            ]);
    }
}
