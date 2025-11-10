<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SolicitacaoDeEntregaStatus: string implements HasColor, HasIcon, HasLabel
{
    case SOLICITADA     = 'Solicitada';
    case CANCELADA      = 'Cancelada';
    case APROVADA     = 'Aprovada';


    public static function values(): array
    {
        return [
            'Solicitada'  => 'Solicitada',
            'Cancelada'   => 'Cancelada',
            'Aprovada'    => 'Aprovada'

        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::SOLICITADA  => 'Solicitada',
            self::CANCELADA   => 'Cancelada',
            self::APROVADA    => 'Aprovada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::SOLICITADA   => 'info',
            self::CANCELADA    => 'danger',
            self::APROVADA     => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SOLICITADA   => 'fas-spinner',
            self::APROVADA     => 'fas-check',
            self::CANCELADA     => 'fas-xmark',
        };
    }
}
