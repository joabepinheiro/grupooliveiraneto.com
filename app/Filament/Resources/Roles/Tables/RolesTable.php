<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
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

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('panel_id')
                    ->label('Painel')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->color(fn ($state)=> match ($state) {
                        'movelveiculos'      => 'movelveiculos',
                        'bydconquista'       => 'bydconquista',
                        'grupooliveiraneto'  => 'grupooliveiraneto',
                        'admin'              => 'admin',
                         default => 'danger',
                    })
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->placeholder('Não informado')
                    ->dateTime('d/m/YH:i')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                Filter::make('panel_id')
                    ->schema([
                        Select::make('panel_id')
                            ->label('Painel')
                            ->placeholder('Todos')
                            ->options([
                                'grupooliveiraneto' => 'Grupo Oliveira Neto',
                                'bydconquista'      => 'BYD Conquista',
                                'movelveiculos'     => 'Movel Veículos',
                                'admin'             => 'Administração',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['panel_id'])){
                            return $query->where('panel_id','=',$data['panel_id']);
                        }
                        return $query;

                    })->columnSpan(3),


                Filter::make('name')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Nome'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('name', '%'.$data['name'].'%');
                    })
                    ->columnSpan(9),


            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
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
