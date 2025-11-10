<?php

namespace App\Filament\Resources\Empresas\Schemas;

use App\Models\Empresa;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmpresaInfolist
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
