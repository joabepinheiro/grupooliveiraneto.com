<?php

namespace App\Filament\Movelveiculos\Widgets;

use Filament\Widgets\ChartWidget;

class AvaliacoesPresenciaisChartPorDia extends ChartWidget
{
    protected ?string $heading = 'Avaliacoes Presenciais Chart Por Dia';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
