<?php

namespace App\Filament\Bydconquista\Resources\Modelos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModeloForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextInput::make('nome')
                            ->label('Nome do modelo')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        TagsInput::make('cores')
                            ->label('Cores')
                            ->required()
                            ->columnSpan([
                                'lg' => 12
                            ]),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('Ativo')
                            ->options([
                                'Ativo'      => 'Ativo',
                                'Desativado' => 'Desativado',
                            ])
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
