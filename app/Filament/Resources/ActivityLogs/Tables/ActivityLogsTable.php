<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Enums\ActivityLogEvent;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('event')
                    ->label('Evento')
                    ->badge()
                    ->color(fn (string $state): string => ActivityLogEvent::from($state)->getColor())
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('subject_id')
                    ->label('ID do registro')
                    ->sortable(),

                TextColumn::make('subject_type')
                    ->label('Tipo')
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('description')
                    ->label('Descrição')
                    ->sortable()
                    ->placeholder('Não informado'),

                TextColumn::make('causer.name')
                    ->label('Usuário')
                    ->placeholder('Não informado'),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->sortable()
                    ->date('d/m/Y H:i:s')
                    ->placeholder('Não informado'),


            ])
            ->filters([
                Filter::make('id')
                    ->schema([
                        TextInput::make('id')
                            ->label('ID')
                            ->placeholder('ID'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('id', '%'.$data['id'].'%');
                    })
                    ->columnSpan(3),

                SelectFilter::make('event')
                    ->label('Evento')
                    ->options(ActivityLogEvent::class)
                    ->columnSpan(4),

                SelectFilter::make('subject_type')
                    ->label('Tipo')
                    ->options([
                        'App\Models\Material\Abertura'   => 'Abertura',
                        'App\Models\Material\Base'       => 'Base',
                        'App\Models\Material\Categoria'  => 'Categoria',
                        'App\Models\Material\Composicao' => 'Composição',
                        'App\Models\Material\Cor'        => 'Cor',
                        'App\Models\Material\Dimensao'   => 'Dimensão',
                        'App\Models\Material\Formato'    => 'Formato',
                        'App\Models\Material\Material'   => 'Material',
                        'App\Models\Material\ModeloSobMedida' => 'Modelo Sob Medida',
                        'App\Models\Material\UnidadeDeMedida' => 'Unidade De Medida',
                        'App\Models\Ambiente'                 => 'Ambiente',
                        'App\Models\Atelie'                   => 'Ateliê',
                        'App\Models\Cliente'                  => 'Cliente',
                        'App\Models\Endereco'                 => 'Endereco',
                        'App\Models\Fornecedor'               => 'Fornecedor',
                        'App\Models\Funcionario'              => 'Funcionário',
                        'App\Models\Nota'                     => 'Nota',
                        'App\Models\Orcamento'                => 'Orcamento',
                        'App\Models\Parametro'                => 'Parametro',
                        'App\Models\Projeto'                  => 'Projeto',
                        'App\Models\Telefone'                 => 'Telefone',
                        'App\Models\User'                     => 'Usuário',
                    ])
                    ->columnSpan(5),

                Filter::make('subject_id')
                    ->schema([
                        TextInput::make('subject_id')
                            ->label('ID do registro')
                            ->placeholder('ID do registro'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('subject_id', '%'.$data['subject_id'].'%');
                    })
                    ->columnSpan(3),

                SelectFilter::make('causer_id')
                    ->label('Usuário')
                    ->options(User::pluck('name', 'id')->toArray())
                    ->columnSpan(4),

                Filter::make('properties')
                    ->schema([
                        TextInput::make('properties')
                            ->label('Propriedades')
                            ->placeholder('Propriedades'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['properties'],
                                fn (Builder $query, $date): Builder => $query->where('properties', 'like', '%'.$date.'%'),
                            );
                    })->columnSpan(5),


                //Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(12)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ])->tooltip('Ações')
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
            ]);
    }
}
