<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EmpresaNome: string implements HasColor, HasIcon, HasLabel
{
    case BYDCONQUISTA   = 'BYD Conquista';
    case MOVELVEICULOS      = 'Movel Veículos';
    case GRUPOOLIVEIRANETO     = 'Grupo Oliveira Neto';


    public static function values(): array
    {
        return [
            'BYD Conquista'  => 'BYD Conquista',
            'Movel Veículos'     => 'Movel Veículos',
            'Grupo Oliveira Neto'    => 'Grupo Oliveira Neto'

        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::BYDCONQUISTA  => 'BYD Conquista',
            self::MOVELVEICULOS     => 'Movel Veículos',
            self::GRUPOOLIVEIRANETO    => 'Grupo Oliveira Neto',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::BYDCONQUISTA   => 'bydconquista',
            self::MOVELVEICULOS      => 'movelveiculos',
            self::GRUPOOLIVEIRANETO     => 'grupooliveiraneto',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BYDCONQUISTA  => 'fas-spinner',
            self::GRUPOOLIVEIRANETO    => 'fas-check',
            self::MOVELVEICULOS     => 'fas-xmark',
        };
    }
}
