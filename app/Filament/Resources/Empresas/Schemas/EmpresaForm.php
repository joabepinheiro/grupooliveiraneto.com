<?php

namespace App\Filament\Resources\Empresas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmpresaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextInput::make('nome')
                            ->label('Nome')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
