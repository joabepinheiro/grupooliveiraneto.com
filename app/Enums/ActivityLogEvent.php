<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActivityLogEvent: string implements HasColor, HasIcon, HasLabel
{

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETE = 'deleted';


    /**
     * Retorna todos os valores do enum
     */
    public static function values(): array
    {
        return [
            'created'     => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
        ];
    }


    public function getLabel(): string
    {
        return match ($this) {
            self::CREATED       => 'created',
            self::UPDATED       => 'updated',
            self::DELETE        => 'deleted',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CREATED  => 'success',
            self::UPDATED  => 'warning',
            self::DELETE   => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREATED => 'fas-plus',
            self::UPDATED => 'fas-pen',
            self::DELETE  => 'fas-trash',
        };
    }
}
