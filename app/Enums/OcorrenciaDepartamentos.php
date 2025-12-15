<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OcorrenciaDepartamentos: string implements HasColor, HasIcon, HasLabel
{
    case TI       = 'TI';
    case CONTABILIDADE   = 'Contabilidade';
    case FINANCEIRO      = 'Financeiro';
    case RH      = 'RH';
    case CRM      = 'CRM';
    case MKT      = 'MKT';
    case COMPRAS      = 'Compras';

    public static function values(): array
    {
        return [
            'TI'            => 'TI',
            'Contabilidade' => 'Contabilidade',
            'Financeiro'    => 'Financeiro',
            'RH'            => 'RH',
            'CRM'           => 'CRM',
            'MKT'           => 'MKT',
            'Compras'       => 'Compras',
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::TI                => 'TI',
            self::CONTABILIDADE     => 'Contabilidade',
            self::FINANCEIRO        => 'Financeiro',
            self::RH                => 'RH',
            self::CRM               => 'CRM',
            self::MKT               => 'MKT',
            self::COMPRAS           => 'Compras',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::TI                => 'gray',
            self::CONTABILIDADE     => 'gray',
            self::FINANCEIRO        => 'gray',
            self::RH                => 'gray',
            self::CRM               => 'gray',
            self::MKT               => 'gray',
            self::COMPRAS           => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TI                => 'fas-computer',
            self::CONTABILIDADE     => 'fas-calculator',
            self::FINANCEIRO        => 'fas-money-check-dollar',
            self::RH                => 'fas-users-between-lines',
            self::CRM               => 'fas-users-rays',
            self::MKT               => 'fas-bullhorn',
            self::COMPRAS           => 'fas-basket-shopping',
        };
    }
}
