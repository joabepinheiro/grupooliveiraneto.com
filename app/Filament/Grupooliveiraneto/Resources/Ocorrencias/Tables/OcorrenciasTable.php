<?php

namespace App\Filament\Grupooliveiraneto\Resources\Ocorrencias\Tables;

use App\Enums\OcorrenciaStatus;
use App\Enums\TarefaStatus;
use App\Models\Tarefa\Ocorrencia;
use App\Models\Tarefa\Tarefa;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OcorrenciasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('data_fim')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state)=> OcorrenciaStatus::from($state)->getColor())
                    ->sortable(),

                TextColumn::make('tarefa.recorrencia_tem')
                    ->label('Recorrência')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Sim' : 'Não'),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->placeholder('Não informada')
                    ->date('d/m/y H:i')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([


                Filter::make('id')
                    ->schema([
                        TextInput::make('id')
                            ->label('ID')
                            ->placeholder('ID'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['id'])){
                            return $query->where('id','=', $data['id']);
                        }
                        return $query;

                    })->columnSpan([
                        'lg' => 2,
                    ]),


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
                        'lg' => 4,
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


                TrashedFilter::make()
                    ->columnSpan([
                        'lg' => 3,
                    ])



            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth('full'),
                    EditAction::make(),
                    Action::make('delete')
                        ->label('Excluir')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Excluir Ocorrência')
                        ->modalDescription('Como você deseja excluir esta ocorrência?')
                        ->schema(function ($record) {
                            // Se não for recorrente, não mostra opções, apenas confirmação padrão
                            if (! $record?->tarefa?->rrule) {
                                return [];
                            }

                            return [
                                Radio::make('scope')
                                    ->label('')
                                    ->options([
                                        'single'    => 'Esta ocorrência',
                                        'following' => 'Esta e as ocorrências seguintes',
                                        'all'       => 'Todas as ocorrências',
                                    ])
                                    ->default('single')
                                    ->required(),
                            ];
                        })
                        ->action(function (array $data, $record) {
                            if (! $record) {
                                return;
                            }

                            $scope = $data['scope'] ?? 'single';

                            // Se não for recorrente, força exclusão única
                            if (! $record->tarefa?->rrule) {
                                $scope = 'single';
                            }

                            switch ($scope) {
                                case 'single':
                                    // Exclui apenas esta ocorrência
                                    $record->delete();
                                    break;

                                case 'following':
                                    // Exclui esta e as futuras da mesma tarefa
                                    Ocorrencia::where('tarefa_id', $record->tarefa_id)
                                        ->where('data_inicio', '>=', $record->data_inicio)
                                        ->delete();
                                    break;

                                case 'all':

                                    Ocorrencia::where('tarefa_id', $record->tarefa_id)
                                        ->delete();

                                    Tarefa::where('id', $record->tarefa_id)
                                        ->delete();

                                    break;
                            }
                        })
                ]),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
