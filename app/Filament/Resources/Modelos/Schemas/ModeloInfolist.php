<?php

namespace App\Filament\Resources\Modelos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModeloInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->schema([
                        TextEntry::make('nome')
                            ->label('Nome')
                            ->placeholder('Não informada')
                            ->columnSpan([
                                'lg' => 12
                            ]),
                    ])
                    ->columns(12),
            ]);
    }
}
