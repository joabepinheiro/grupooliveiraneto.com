<?php

namespace App\Filament\Movelveiculos\Widgets;


use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstatisticasGerais extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Estatísticas Gerais';


    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? Carbon::now()->subMonth()->format('Y-m-d 00:00:00');
        $endDate   = $this->filters['endDate'] ?? Carbon::now()->format('Y-m-d 23:59:59');


        $query = Entrega::query();

        $query->whereDate('created_at' ,'>=', $startDate);
        $query->whereDate('created_at' ,'<=', $endDate);

        $mes_anterior = Carbon::now()->subMonth();

        return [
            Stat::make(
                '',
                Entrega::where('status', 'Finalizada')
                    ->where('entrega_efetivada_em', '>=', $startDate)
                    ->where('entrega_efetivada_em', '<=', $endDate)
                    ->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                    ->count()
            )->description('Entregas finalizadas'),

            Stat::make(
                '',
                Entrega::where('status', 'Em andamento')
                    ->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                    ->count()
            )->description('Entregas em andamento'),

            Stat::make(
                '',
                Entrega::where('status', 'Cancelada')
                    ->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                    ->count()
            )->description('Entregas canceladas'),
        ];
    }

}
