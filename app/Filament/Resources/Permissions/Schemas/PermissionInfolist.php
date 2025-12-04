<?php

namespace App\Filament\Resources\Permissions\Schemas;

use App\Enums\EntregaStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([

                        TextEntry::make('panel_id')
                            ->label('Painel')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('tipo')
                            ->label('Tipo')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextEntry::make('action')
                            ->label('Action')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),




                        TextEntry::make('name')
                            ->label('Name')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('className')
                            ->label('ClassName')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 3
                            ]),


                        TextEntry::make('guard_name')
                            ->label('Guard Name')
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

                Section::make('Funções que tem essa permissão')
                    ->schema([
                        RepeatableEntry::make('roles')
                            ->hiddenLabel(true)
                            ->schema([
                                TextEntry::make('name')
                                    ->bulleted()
                                    ->hiddenLabel(true),
                            ])
                            ->placeholder('Nenhum acessório informado')
                            ->contained(false)
                            ->columns(1)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
