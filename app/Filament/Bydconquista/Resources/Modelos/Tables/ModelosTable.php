<?php

namespace App\Filament\Bydconquista\Resources\Modelos\Tables;

use App\Enums\ModeloStatus;
use App\Models\Empresa;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ModelosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Modelo')
                    ->placeholder('Não informada')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cores')
                    ->badge()
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
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('empresa_id', '=', Empresa::BYD_CONQUISTA_ID);
            })
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
