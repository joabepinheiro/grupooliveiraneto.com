<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Tables;

use App\Enums\EntregaStatus;
use App\Enums\TarefaStatus;
use App\Models\Empresa;
use App\Models\Modelo;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TarefasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state)=> TarefaStatus::from($state)->getColor())
                    ->sortable(),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('data_fim')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                // Mostrar se é recorrente
                TextColumn::make('recorrencia_tem')
                    ->label('Recorrência')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Sim' : 'Não'),

                // Contagem de ocorrências
                TextColumn::make('ocorrencias_count')
                    ->label('Ocorrências')
                    ->counts('ocorrencias')
                    ->sortable(),


                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->placeholder('Não informada')
                    ->date('d/m/y H:i')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('deleted_at')
                    ->label('Excluído em')
                    ->placeholder('Não informada')
                    ->date('d/m/y H:i')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),

                TernaryFilter::make('rrule')
                    ->label('Recorrência')
                    ->trueLabel('Apenas recorrentes')
                    ->falseLabel('Apenas únicas')
                    ->placeholder('Todas'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(TarefaStatus::values()),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth('full'),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ], position: RecordActionsPosition::BeforeColumns)
            ->filters([

                Filter::make('titulo')
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->placeholder('Título'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['titulo'])){
                            return $query->whereLike('titulo','%'.$data['titulo'].'%');
                        }
                        return $query;

                    })->columnSpan([
                        'lg' => 3,
                    ]),


                Filter::make('status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Todos')
                            ->prefixIcon(fn ($state) => TarefaStatus::tryFrom($state)?->getIcon())
                            ->prefixIconColor(fn ($state) => TarefaStatus::tryFrom($state)?->getColor())
                            ->options(TarefaStatus::values()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['status'])){
                            return $query->where('status','=',$data['status']);
                        }
                        return $query;

                    })->columnSpan(3),

                SelectFilter::make('recorrencia_tem')
                    ->label('Tem recorrência')
                    ->options([
                        1 => 'Sim',
                        0 => 'Não',
                    ])
                    ->columnSpan([
                        'lg' => 3,
                    ]),

                TrashedFilter::make()
                    ->columnSpan([
                        'lg' => 3,
                    ])



            ], layout: FiltersLayout::AboveContent)
                ->filtersFormColumns(12)
                ->deferFilters(false)
                ->searchable(false)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
