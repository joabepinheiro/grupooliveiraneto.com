<?php

namespace App\Filament\Bydconquista\Widgets;

use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class EntregasChartPorModelo extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '180px';
    protected ?string $heading = 'Entregas Chart Por Modelo';
    protected ?string $description = '';

    protected static ?int $sort = 10;

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? Carbon::now()->subMonth()->format('Y-m-d 00:00:00');
        $endDate   = $this->filters['endDate'] ?? Carbon::now()->format('Y-m-d 23:59:59');

        $this->description = 'Informações referentes ao período de '. Carbon::create($startDate)->format('d/m/Y'). ' até '. Carbon::create($endDate)->format('d/m/Y');


        $query = Entrega::query()
            ->where('status', 'Finalizada')
            ->where('entrega_efetivada_em', '>=', $startDate)
            ->where('entrega_efetivada_em', '<=', $endDate)
            ->where('empresa_id', '=', Empresa::BYD_CONQUISTA_ID)
            ->selectRaw('modelo as data')
            ->selectRaw('COUNT(*) as quantidade')
            ->groupByRaw('modelo')
            ->orderBy('entrega_efetivada_em', 'ASC');

        // Obter os resultados como um array associativo
        $result = $query->pluck('quantidade', 'data')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade',
                    'data' => array_values($result),
                    'backgroundColor' => [
                        '#ff6f1e',
                    ],
                    'borderColor' => '#000',
                    'borderWidth' => 2,
                ],


            ],
            'labels' => array_keys($result),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'title' => [
                    'display' => false,
                ],
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
