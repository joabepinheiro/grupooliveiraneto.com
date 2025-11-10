<?php

namespace App\Filament\Resources\SolicitacaoDeEntregas\Tables;

use App\Enums\EmpresaNome;
use App\Enums\SolicitacaoDeEntregaStatus;
use App\Filament\Resources\Entregas\EntregaResource;
use App\Filament\Resources\SolicitacaoDeEntregas\SolicitacaoDeEntregaResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Entrega;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SolicitacaoDeEntregasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderableColumns()
            ->columns([

                TextColumn::make('empresa.nome')
                    ->label('Empresa')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

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

                Filter::make('empresa_id')
                    ->schema([
                        Select::make('empresa_id')
                            ->label('Empresas')
                            ->multiple()
                            ->options(auth()->user()->empresas->pluck('nome', 'id')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['empresa_id'])){
                            return $query->whereIn('empresa_id', $data['empresa_id']);
                        }
                        return $query;

                    })->columnSpanFull(),

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
                            ->prefixIcon(UserResource::getNavigationIcon())
                            ->placeholder('Nome do cliente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('cliente', 'like', '%'.$data['cliente_nome'].'%');
                    })->columnSpan(6),

                Filter::make('entrega_id')
                    ->schema([
                        TextInput::make('entrega_id')
                            ->label('Cód. entrega')
                            #->prefixIcon(ENTREGAr::getNavigationIcon())
                            ->placeholder('Cód. projeto'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('entrega_id', '%'.$data['entrega_id'].'%');
                    })
                    ->columnSpan(3),

                Filter::make('vendedor_nome')
                    ->schema([
                        TextInput::make('vendedor_nome')
                            ->label('Vendedor')
                            ->prefixIcon(UserResource::getNavigationIcon())
                            ->placeholder('Nome do vendedor'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['vendedor_nome'] ?? null, function (Builder $query, $nome) {
                            return $query
                                ->where(function (Builder $q) use ($nome) {
                                    $q->whereHas('vendedor', function ($sub) use ($nome) {
                                        $sub->where('name', 'like', '%' . $nome . '%');
                                    });
                                });
                        });
                    })->columnSpan(6),





                //TrashedFilter::make()->columnSpan(3),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->recordActions([
                ActionGroup::make([

                    Action::make('aprovar')
                        ->label('Aprovar')
                        ->icon('fas-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Entrega\SolicitacaoDeEntrega $record) {

                            $entrega = Entrega\Entrega::create([
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
                        ->action(function (Entrega\SolicitacaoDeEntrega $record) {
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
