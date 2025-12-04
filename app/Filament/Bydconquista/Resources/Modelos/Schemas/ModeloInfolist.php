<?php

namespace App\Filament\Bydconquista\Resources\Modelos\Schemas;

use App\Models\Modelo;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ModeloInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('empresa_id')
                    ->numeric(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Modelo $record): bool => $record->trashed()),
            ]);
    }
}
