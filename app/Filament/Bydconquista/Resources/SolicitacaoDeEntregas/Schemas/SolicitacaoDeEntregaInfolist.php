<?php

namespace App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Schemas;

use App\Enums\SolicitacaoDeEntregaStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SolicitacaoDeEntregaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Dados da solicitação de de entrega')
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
                            ->color(fn ($state)=> SolicitacaoDeEntregaStatus::from($state)->getColor())
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

                Section::make('Histórico de alterações do status')
                    ->schema([
                        RepeatableEntry::make('activities')
                            ->hiddenLabel(true)
                            ->schema([

                                TextEntry::make('created_at')
                                    ->label('Alteração feita em')
                                    ->dateTime('d/m/y H:i:s'),

                                TextEntry::make('causer.name')
                                    ->label('Usuário'),

                                TextEntry::make('properties.attributes.status')
                                    ->badge()
                                    ->color(fn ($state)=> SolicitacaoDeEntregaStatus::from($state)->getColor())
                                    ->placeholder('Não informada')
                                    ->label('Status'),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                    ])

            ]);
    }
}
