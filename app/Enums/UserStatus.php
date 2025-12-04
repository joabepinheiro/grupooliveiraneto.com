<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserStatus: string implements HasColor, HasIcon, HasLabel
{
    case ATIVO          = 'Ativo';
    case DESATIVADO      = 'Desativado';


    public static function values(): array
    {
        return [
            'Ativo'         => 'Ativo',
            'Desativado'    => 'Desativado',
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ATIVO          => 'Ativo',
            self::DESATIVADO     => 'Desativado',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::ATIVO          => 'success',
            self::DESATIVADO     => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ATIVO          => 'fas-check',
            self::DESATIVADO     => 'fas-xmark',
        };
    }
}
