<?php

namespace App\Filament\Resources\Modelos\Tables;

use App\Enums\ModeloStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ModelosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('empresa.nome')
                    ->label('Empresa')
                    ->placeholder('Não informada')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('nome')
                    ->label('Modelo')
                    ->placeholder('Não informada')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cores')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->label('Cores')
                    ->placeholder('Não informada')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->placeholder('Não informada')
                    ->badge()
                    ->color(fn ($state): string => ModeloStatus::tryFrom($state->value)?->getColor())
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                //ViewAction::make(),
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
