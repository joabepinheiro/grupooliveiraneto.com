<?php

namespace App\Filament\Resources\SolicitacaoDeEntregas\Schemas;

use App\Enums\SolicitacaoDeEntregaStatus;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class SolicitacaoDeEntregaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }
}
