<?php

namespace App\Filament\Bydconquista\Resources\ActivityLogs\Schemas;

use App\Enums\ActivityLogEvent;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([

                        TextEntry::make('id')
                            ->label('ID')
                            ->default('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('event')
                            ->label('Evento')
                            ->default('Não informada')
                            ->badge()
                            ->color(fn (string $state): string => ActivityLogEvent::from($state)->getColor())
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('causer.name')
                            ->label('Usuário')
                            ->default('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('subject_id')
                            ->label('ID do registro')
                            ->default('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('created_at')
                            ->label('Cadastrado em')
                            ->date('d/m/Y H:i:s')
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('subject_type')
                            ->label('Tipo')
                            ->default('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('properties.ip')
                            ->label('IP')
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        TextEntry::make('properties.browser')
                            ->label('Navegador')
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('properties.device')
                            ->label('Dispositivo')
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('properties.platform')
                            ->label('Plataforma')
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('properties.user_agent')
                            ->label('User Agent')
                            ->placeholder('Não informado')
                            ->columnSpanFull(),

                    ])
                    ->columns(12)
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([

                        KeyValueEntry::make('properties.old')
                            ->hidden(fn ($get) => empty($get('properties.old')))
                            ->extraAttributes(['class' => 'max-w'])
                            ->label('Registro antes da alteração'),

                        KeyValueEntry::make('properties.attributes')
                            ->hidden(fn ($get) => empty($get('properties.attributes')))
                            ->extraAttributes(['class' => 'max-w'])
                            ->label('Registro'),

                        KeyValueEntry::make('properties.new')
                            ->hidden(fn ($get) => empty($get('properties.new')))
                            ->extraAttributes(['class' => 'max-w'])
                            ->label('Dados alterados'),

                    ])->columnSpanFull(),


            ]);
    }
}
