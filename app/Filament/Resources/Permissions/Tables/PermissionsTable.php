<?php

namespace App\Filament\Resources\Permissions\Tables;

use App\Models\Empresa;
use App\Models\User;
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

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('panel_id')
                    ->label('Painel ID')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Funções')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->placeholder('Não informado'),

                TextColumn::make('name')
                    ->label('Name')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('action')
                    ->label('Action')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('className')
                    ->label('Classe Name')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('panel_id')
                    ->label('Painel')
                    ->options([
                        'grupooliveiraneto' => 'Grupo Oliveira Neto',
                        'bydconquista'      => 'BYD Conquista',
                        'movelveiculos'     => 'Movel Veículos',
                        'admin'             => 'Administração',
                    ])
                    ->searchable()
                    ->columnSpan(6),

                SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Model'     => 'Model',
                        'widget'    => 'Widget',
                        'page'      => 'Page',
                        'custom'    => 'Custom',
                    ] )
                    ->searchable()
                    ->columnSpan(6),


                Filter::make('name')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Nome'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('name', '%'.$data['name'].'%');
                    })
                    ->columnSpan(6),


                Filter::make('descricao')
                    ->schema([
                        TextInput::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Descrição'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('descricao', '%'.$data['descricao'].'%');
                    })
                    ->columnSpan(6),


            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
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
