<?php

namespace App\Filament\Bydconquista\Resources\SolicitacaoDeEntregas\Tables;

use App\Enums\SolicitacaoDeEntregaStatus;
use App\Filament\Resources\Entregas\EntregaResource;
use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
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
use Illuminate\Support\HtmlString;

class SolicitacaoDeEntregasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderableColumns()
            ->columns([
                TextColumn::make('proposta')
                    ->label('Proposta')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->placeholder('Todos')
                    ->badge()
                    ->html()
                    ->color(fn ($state)=> SolicitacaoDeEntregaStatus::from($state)->getColor())
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipo_venda')
                    ->label('Tipo de venda')
                    ->sortable()
                    ->placeholder('Não informado')
                    ->searchable(),

                TextColumn::make('id')
                    ->label('Carro')
                    ->view('filament.tables.columns.solicitacao_de_entregas.solicitacao_de_visitas')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('data_prevista')
                    ->label(new HtmlString('Data prevista<br/>para entrega'))
                    ->view('filament.tables.columns.solicitacao_de_entregas.data_prevista')
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->forceSearchCaseInsensitive()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('vendedor.name')
                    ->label('Vendedor')
                    ->forceSearchCaseInsensitive()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(new HtmlString('Enviado em'))
                    ->view('filament.tables.columns.solicitacao_de_entregas.created_at')
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Todos')
                            ->prefixIcon(fn ($state) => SolicitacaoDeEntregaStatus::tryFrom($state)?->getIcon())
                            ->prefixIconColor(fn ($state) => SolicitacaoDeEntregaStatus::tryFrom($state)?->getColor())
                            ->options(SolicitacaoDeEntregaStatus::values()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['status'])){
                            return $query->where('status','=',$data['status']);
                        }
                        return $query;

                    })->columnSpan(3),

                Filter::make('proposta')
                    ->schema([
                        TextInput::make('proposta')
                            ->label('Proposta')
                            ->placeholder('Proposta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('proposta', '%'.$data['proposta'].'%');
                    })
                    ->columnSpan(3),

                Filter::make('cliente_nome')
                    ->schema([
                        TextInput::make('cliente_nome')
                            ->label('Cliente')
                            ->placeholder('Nome do cliente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('cliente', 'like', '%'.$data['cliente_nome'].'%');
                    })->columnSpan(6),

                Filter::make('chassi')
                    ->schema([
                        TextInput::make('chassi')
                            ->label('Chassi')
                            ->placeholder('Chassi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('chassi', 'like', '%'.$data['chassi'].'%');
                    })->columnSpan(3),

                Filter::make('modelo')
                    ->schema([
                        TextInput::make('modelo')
                            ->label('Modelo')
                            ->placeholder('Modelo'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('modelo', 'like', '%'.$data['modelo'].'%');
                    })->columnSpan(3),

                SelectFilter::make('vendedor_id')
                    ->label('Vendedor')
                    ->options(
                        User::whereHas('empresas', fn (Builder $query) =>
                        $query->where('empresas.id', '=', Empresa::BYD_CONQUISTA_ID))
                        ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->columnSpan(6),

                //TrashedFilter::make()->columnSpan(3),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('empresa_id', '=', Empresa::BYD_CONQUISTA_ID);
            })
            ->recordActions([
                ActionGroup::make([

                    Action::make('aprovar')
                        ->label('Aprovar')
                        ->icon('fas-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (SolicitacaoDeEntrega $record) {

                            $entrega = Entrega::create([
                                'tipo_venda'                 => $record->tipo_venda,
                                'solicitacoes_de_entrega_id' => $record->id,
                                'data_prevista'              => $record->data_prevista,
                                'proposta'                   => $record->proposta,
                                'cliente'                    => $record->cliente,
                                'vendedor_id'                => $record->vendedor_id,
                                'modelo'                     => $record->modelo,
                                'cor'                        => $record->cor,
                                'chassi'                     => $record->chassi,

                                'foi_solicitado_emplacamento'   => $record->foi_solicitado_emplacamento,
                                'foi_solicitado_acessorio'      => $record->foi_solicitado_acessorio,
                                'acessorios_solicitados'        => $record->acessorios_solicitados,
                                'brinde'                        => $record->brinde,
                            ]);

                            $record->update([
                                'status'     => 'Aprovada',
                                'entrega_id' => $entrega->id
                            ]);
                        })
                        ->visible(function ($record) {
                            if($record->status != 'Aprovada'){
                                return true;
                            }

                            return false;
                        }),

                    Action::make('entrega')
                        ->label('Ver entrega')
                        ->icon('fas-link')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->url(function ($record){
                            return EntregaResource::getUrl('view', ['record' => $record->entrega_id]);
                        })
                        ->visible(function ($record) {
                            if($record->status == 'Aprovada'){
                                return true;
                            }

                            return false;
                        }),

                    Action::make('cancelar')
                        ->label('Cancelar')
                        ->icon('fas-xmark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (SolicitacaoDeEntrega $record) {
                            $record->update([
                                'status'     => 'Cancelada',
                            ]);
                        })
                        ->visible(function ($record) {
                            if($record->status != 'Cancelada'){
                                return true;
                            }

                            return false;
                        }),

                    EditAction::make('editar')
                        ->icon('fas-pencil')
                        ->label('Editar'),
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
