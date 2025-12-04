<?php

namespace App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Schemas;

use App\Enums\EntregaStatus;
use App\Models\Entrega\EntregaHorarioBloqueado;
use App\Models\Modelo;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SolicitacaoDeEntregaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }
}
