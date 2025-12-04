<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Role;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
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
                                'lg' => 6
                            ]),

                        TextEntry::make('name')
                            ->label('Nome')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('created_at')
                            ->label('Cadastrado em')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        RepeatableEntry::make('permissions')
                            ->label('Permissões')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nome')
                                    ->columnSpanFull(),

                                TextEntry::make('tipo')
                                    ->label('Tipo')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),

                                TextEntry::make('descricao')
                                    ->label('Descrição')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),


                                TextEntry::make('className')
                                    ->label('className')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),


                                TextEntry::make('action')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),
                                TextEntry::make('guard_name')
                                    ->columnSpan([
                                        'lg' => 4
                                    ]),
                            ])
                            ->columns(12)
                            ->columnSpanFull()
                    ])
                    ->columns(12),


            ]);
    }
}
