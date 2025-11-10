<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EntregaStatus: string implements HasColor, HasIcon, HasLabel
{
    case EM_ANDAMENTO   = 'Em andamento';
    case CANCELADA      = 'Cancelada';
    case FINALIZADA     = 'Finalizada';


    public static function values(): array
    {
        return [
            'Em andamento'  => 'Em andamento',
            'Cancelada'     => 'Cancelada',
            'Finalizada'    => 'Finalizada'

        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::EM_ANDAMENTO  => 'Em andamento',
            self::CANCELADA     => 'Cancelada',
            self::FINALIZADA    => 'Finalizada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::EM_ANDAMENTO   => 'info',
            self::CANCELADA      => 'danger',
            self::FINALIZADA     => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::EM_ANDAMENTO  => 'fas-spinner',
            self::FINALIZADA    => 'fas-check',
            self::CANCELADA     => 'fas-xmark',
        };
    }
}
