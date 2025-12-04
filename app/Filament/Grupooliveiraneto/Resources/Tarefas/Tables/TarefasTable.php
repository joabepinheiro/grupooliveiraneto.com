<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TarefasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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
                TextColumn::make('rrule')
                    ->label('Recorrência')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Sim' : 'Não')
                    ->colors([
                        'primary' => fn ($state) => $state !== null,
                    ]),



                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pendente',
                        'info'    => 'andamento',
                        'success' => 'concluida',
                        'danger'  => 'cancelada',
                    ])
                    ->sortable(),

                // Contagem de ocorrências
                TextColumn::make('ocorrencias_count')
                    ->label('Ocorrências')
                    ->counts('ocorrencias')
                    ->sortable(),
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
                    ->options([
                        'pendente'   => 'Pendente',
                        'andamento'  => 'Em andamento',
                        'concluida'  => 'Concluída',
                        'cancelada'  => 'Cancelada',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
