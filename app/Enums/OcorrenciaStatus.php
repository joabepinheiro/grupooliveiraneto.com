<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OcorrenciaStatus: string implements HasColor, HasIcon, HasLabel
{
    case PENDENTE       = 'Pendente';
    case EM_ANDAMENTO   = 'Em andamento';
    case CONCLUIDO      = 'Concluído';


    public static function values(): array
    {
        return [
            'Pendente'          => 'Pendente',
            'Em andamento'      => 'Em andamento',
            'Concluído'         => 'Concluído'

        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDENTE          => 'Pendente',
            self::EM_ANDAMENTO      => 'Em andamento',
            self::CONCLUIDO         => 'Concluído',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::PENDENTE          => 'warning',
            self::EM_ANDAMENTO      => 'info',
            self::CONCLUIDO         => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDENTE          => 'fas-xmark',
            self::EM_ANDAMENTO      => 'fas-spinner',
            self::CONCLUIDO         => 'fas-check',
        };
    }
}
