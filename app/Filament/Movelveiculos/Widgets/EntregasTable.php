<?php

namespace App\Filament\Movelveiculos\Widgets;

use App\Enums\EntregaStatus;
use App\Models\Entrega\Entrega;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EntregasTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading  = 'Entregas';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Entrega::query())
            ->striped(true)

            ->columns([

                TextColumn::make('row_index')
                ->label('#')
                ->rowIndex(),

                TextColumn::make('data_prevista')
                    ->label(new HtmlString('Previsão<br/> de entrega'))
                    ->html()
                    ->formatStateUsing(function ($state){
                        $data =  Carbon::make($state);
                        return $data->format('H:i') . '<br/>'.$data->format('d/m/Y') . '<br/>' . $data->translatedFormat('l');
                    })
                    ->placeholder('Não informada')
                    ->sortable(),


                TextColumn::make('proposta')
                    ->label('Entrega')
                    ->placeholder('Não informado')
                    ->view('filament.tables.columns.entregas_de_hoje.entrega')
                    ->sortable(),

                TextColumn::make('modelo')
                    ->label('Modelo')
                    ->placeholder('Não informado')
                    ->view('filament.tables.columns.entregas_de_hoje.modelo')
                    ->sortable(),


                TextColumn::make('entrega_efetivada_em')
                    ->label(new HtmlString('Entrega<br/>efetivada em'))
                    ->placeholder('Não informada')
                    ->date('d/m/y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->html()
                    ->placeholder('Não informada')
                    ->color(fn ($state)=> EntregaStatus::from($state)->getColor())
                    ->sortable(),


            ])
            ->defaultKeySort('data_prevista', 'desc')
            ->searchable(false)
            ->collapsedGroupsByDefault(false)
            ->paginated(true)
            ->defaultPaginationPageOption(50)
            ->filters([
                Filter::make('periodo')
                    ->schema([
                        // 1. O Seletor de Atalhos
                        Select::make('tipo')
                            ->label('Período')
                            ->prefixIcon('fas-calendar-week')
                            ->options([
                                'hoje' => 'Hoje',
                                'amanha' => 'Amanhã', // ✅ NOVO
                                'semana-atual' => 'Semana atual',
                                'semana-que-vem' => 'Semana que vem', // ✅ NOVO
                                'semana-anterior' => 'Semana anterior',
                                'mes-atual' => 'Mês atual',
                                'proximo-mes' => 'Próximo mês', // ✅ NOVO
                                'mes-anterior' => 'Mês anterior',
                                'personalizado' => 'Personalizado',
                            ])
                            ->live()
                            ->default('semana-atual')
                            ->afterStateUpdated(function ($state, Set $set) {

                                // Se o usuário limpar a seleção de tipo, limpa também as datas
                                if (! $state) {
                                    $set('startDate', null);
                                    $set('endDate', null);
                                    return;
                                }

                                $now = Carbon::now();
                                $nowCopy = $now->copy(); // Usa uma cópia para evitar modificar $now

                                // Lógica para definir as datas baseadas na seleção
                                [$start, $end] = match ($state) {
                                    // Hoje: inclui o tempo (startOfDay e endOfDay)
                                    'hoje' => [
                                        $nowCopy->startOfDay()->toDateTimeString(),
                                        $nowCopy->endOfDay()->toDateTimeString()
                                    ],

                                    // ✅ NOVO: Amanhã
                                    'amanha' => [
                                        $nowCopy->addDay()->startOfDay()->toDateTimeString(),
                                        $nowCopy->endOfDay()->toDateTimeString()
                                    ],

                                    // Semana Atual: datas simples (toDateString)
                                    'semana-atual' => [
                                        $nowCopy->startOfWeek()->toDateString(),
                                        $nowCopy->endOfWeek()->toDateString()
                                    ],

                                    // ✅ NOVO: Semana que vem
                                    'semana-que-vem' => [
                                        $nowCopy->addWeek()->startOfWeek()->toDateString(),
                                        $nowCopy->endOfWeek()->toDateString()
                                    ],

                                    // Semana Anterior: usa ->copy() para não modificar a instância $now original
                                    'semana-anterior' => [
                                        $nowCopy->subWeek()->startOfWeek()->toDateString(),
                                        $nowCopy->endOfWeek()->toDateString()
                                    ],

                                    // Mês Atual: datas simples
                                    'mes-atual' => [
                                        $nowCopy->startOfMonth()->toDateString(),
                                        $nowCopy->endOfMonth()->toDateString()
                                    ],

                                    // ✅ NOVO: Próximo mês
                                    'proximo-mes' => [
                                        $nowCopy->addMonth()->startOfMonth()->toDateString(),
                                        $nowCopy->endOfMonth()->toDateString()
                                    ],

                                    // Mês Anterior: usa ->copy()
                                    'mes-anterior' => [
                                        $nowCopy->subMonth()->startOfMonth()->toDateString(),
                                        $nowCopy->subMonth()->endOfMonth()->toDateString()
                                    ],

                                    default => [null, null],
                                };

                                // Preenche os campos de data
                                $set('startDate', $start);
                                $set('endDate', $end);
                            })
                            ->columnSpan([
                                'lg' => 5
                            ]),
                        FusedGroup::make([
                            DatePicker::make('startDate')
                                ->prefix('De')
                                ->default(now()->startOfWeek())
                                ->afterStateUpdated(function ($state, Set $set){
                                    $set('tipo', 'personalizado');
                                }),

                            DatePicker::make('endDate')
                                ->prefix('Até')
                                ->default(now()->endOfWeek())
                                ->afterStateUpdated(function ($state, Set $set){
                                    $set('tipo', 'personalizado');
                                }),
                        ])
                        ->label('Entregas previstas')
                        ->columns(2)
                        ->columnSpan([
                            'lg' => 7
                        ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Aplica o filtro na query usando as datas preenchidas (manual ou por 'tipo')
                        return $query
                            ->when(
                                $data['startDate'],
                                fn (Builder $query, $date) => $query->whereDate('data_prevista', '>=', $date),
                            )
                            ->when(
                                $data['endDate'],
                                fn (Builder $query, $date) => $query->whereDate('data_prevista', '<=', $date),
                            );
                    })
                    ->columns(12)
                    ->columnSpan('full'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
