<?php

namespace App\Filament\Movelveiculos\Pages;





// Adicionado o HasFiltersForm ao use
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Carbon;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {

        if(!isset($this->filters['startDate'])){
            $this->filters['startDate'] = Carbon::now()->subMonth(1)->format('Y-m-d');
        }

        if(!isset($this->filters['endDate'])){
            $this->filters['endDate'] = now()->format('Y-m-d');;
        }

        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Data inicial')
                            ->format('Y-m-d')
                            ->afterStateUpdated(function ($state, Set $set){
                                $set('tipo', 'personalizado');
                            })
                            ->columnSpan(3),

                        DatePicker::make('endDate')
                            ->label('Data Final')
                            ->format('Y-m-d')
                            ->afterStateUpdated(function ($state, Set $set){
                                $set('tipo', 'personalizado');
                            })
                            ->columnSpan(3),

                        Select::make('tipo')
                            ->options([
                                'personalizado' => 'Personalizado',
                                'semana-atual' => 'Semana atual',
                                'semana-anterior' => 'Semana anterior',
                                'mes-atual' => 'Mes atual',
                                'mes-anterior' => 'Mes anterior',
                                'ano-atual' => 'Ano atual',
                                'ano-anterior' => 'Ano anterior',
                            ])
                            ->afterStateUpdated(function ($state, Set $set){
                                $carbon = Carbon::now();

                                if($state == 'semana-atual'){
                                    $set('startDate', $carbon->now()->startOfWeek()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->format('Y-m-d'));
                                }

                                if($state == 'semana-anterior'){
                                    $set('startDate', $carbon->now()->startOfWeek()->subWeek()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->endOfWeek()->subWeek()->format('Y-m-d'));
                                }

                                if($state == 'mes-atual'){
                                    $set('startDate', $carbon->now()->startOfMonth()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->format('Y-m-d'));
                                }

                                if($state == 'mes-anterior'){
                                    $set('startDate', $carbon->now()->startOfMonth()->subMonth()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->subMonth()->endOfMonth()->format('Y-m-d'));
                                }

                                if($state == 'ano-atual'){
                                    $set('startDate', $carbon->now()->startOfYear()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->format('Y-m-d'));
                                }

                                if($state == 'ano-anterior'){
                                    $set('startDate', $carbon->now()->subYear()->startOfYear()->format('Y-m-d'));
                                    $set('endDate', $carbon->now()->subYear()->endOfYear()->format('Y-m-d'));
                                }
                            })
                            ->label('Escala')
                            ->columnSpan(6),
                    ])
                    ->columnSpanFull()
                    ->columns(12),
            ]);
    }

}
