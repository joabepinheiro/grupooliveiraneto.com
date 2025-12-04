<?php

namespace App\Filament\Bydconquista\Resources\Entregas\Tables;

use App\Enums\EntregaStatus;
use App\Models\Empresa;
use App\Models\Modelo;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EntregasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proposta')
                    ->label('Proposta')
                    ->placeholder('Não informado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->html()
                    ->color(fn ($state)=> EntregaStatus::from($state)->getColor())
                    ->sortable()
                    ->searchable(),

                TextColumn::make('financeiro_autorizada_pelo_financeiro')
                    ->label(new HtmlString('Autorizada<br/>pelo financeiro'))
                    ->badge()
                    ->formatStateUsing(function ($state){
                        if($state == 1){
                            return 'Autorizada';
                        }else{
                            return 'Não Autorizada';
                        }
                    })
                    ->color(fn ($state)=> match ($state) {
                        true, 1 => 'success',
                        default => 'danger',
                    })
                    ->sortable()
                    ->searchable(),


                TextColumn::make('data_prevista')
                    ->label('Data prevista para entrega')
                    ->date('d/m/y H:i')
                    ->wrap()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('entrega_efetivada_em')
                    ->label('Entrega efetivada em')
                    ->placeholder('Não informada')
                    ->date('d/m/y H:i')
                    ->wrap()
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

                TextColumn::make('modelo')
                    ->label('Modelo')
                    ->forceSearchCaseInsensitive()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('chassi')
                    ->label('Chassi')
                    ->placeholder('Não informado')
                    ->forceSearchCaseInsensitive()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cor')
                    ->label('Cor')
                    ->forceSearchCaseInsensitive()
                    ->placeholder('Não informada')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('pesquisa_com_7_dias_finalizada')
                    ->label(new HtmlString('Pesquisa<br/>7 dias'))
                    ->badge()
                    ->formatStateUsing(function ($state){
                        if($state == 1){
                            return 'Finalizada';
                        }else{
                            return 'Não finalizada';
                        }
                    })
                    ->color(fn ($state)=> match ($state) {
                        true, 1 => 'success',
                        default => 'danger',
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(function (){
                        if(auth()->user()->roles->contains('name', 'Gerente de vendas') || auth()->user()->roles->contains('name', 'Secretária de vendas')){
                            return true;
                        }
                        return false;
                    }),

                TextColumn::make('pesquisa_com_30_dias_finalizada')
                    ->label(new HtmlString('Pesquisa<br/>30 dias'))
                    ->badge()
                    ->formatStateUsing(function ($state){
                        if($state == 1){
                            return 'Finalizada';
                        }else{
                            return 'Não finalizada';
                        }
                    })
                    ->color(fn ($state)=> match ($state) {
                        true, 1 => 'success',
                        default => 'danger',
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(function (){
                        if(auth()->user()->roles->contains('name', 'Gerente de vendas') || auth()->user()->roles->contains('name', 'Secretária de vendas')){
                            return true;
                        }
                        return false;
                    }),
            ])
            ->filters([

                Filter::make('status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->placeholder('Todos')
                            ->prefixIcon(fn ($state) => EntregaStatus::tryFrom($state)?->getIcon())
                            ->prefixIconColor(fn ($state) => EntregaStatus::tryFrom($state)?->getColor())
                            ->options(EntregaStatus::values()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['status'])){
                            return $query->where('status','=',$data['status']);
                        }
                        return $query;

                    })->columnSpan(3),

                SelectFilter::make('financeiro_autorizada_pelo_financeiro')
                    ->label('Autorizado pelo financeiro')
                    ->options([
                        1 => 'Autorizada',
                        0 => 'Não autorizada',
                    ])
                    ->columnSpan(3),

                SelectFilter::make('modelo')
                    ->label('Modelo')
                    ->options(Modelo::query()
                        ->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                        ->get()
                        ->pluck('nome', 'nome')
                        ->toArray())
                    ->columnSpan(3),

                SelectFilter::make('vendedor_id')
                    ->label('Vendedor')
                    ->options(User::query()
                        ->with('roles')
                        ->whereHas('roles', function ($query) {
                            $query->where('name', '=', 'Vendedor');
                        })
                        ->get()
                        ->pluck('name', 'id')
                        ->toArray())
                    ->columnSpan(3),

                Filter::make('cliente')
                    ->schema([
                        TextInput::make('cliente')
                            ->label('Cliente')
                            ->placeholder('Cliente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('cliente', '%'.$data['cliente'].'%');
                    })
                    ->columnSpan(3),

                Filter::make('chassi')
                    ->schema([
                        TextInput::make('chassi')
                            ->label('Chassi')
                            ->placeholder('Chassi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('chassi', '%'.$data['chassi'].'%');
                    })
                    ->columnSpan(3),



                Filter::make('entrega_efetivada_de')
                    ->schema([
                        DatePicker::make('entrega_efetivada_de')
                            ->label('Entrega efetivada de')
                            ->placeholder('Entrega efetivada de'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['entrega_efetivada_de'],
                                fn (Builder $query, $date): Builder => $query->whereDate('entrega_efetivada_em', '>=', $date),
                            );
                    })
                    ->columnSpan(3),

                Filter::make('entrega_efetivada_ate')
                    ->schema([
                        DatePicker::make('entrega_efetivada_ate')
                            ->label('Entrega efetivada até')
                            ->placeholder('Entrega efetivada até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['entrega_efetivada_ate'],
                                fn (Builder $query, $date): Builder => $query->whereDate('entrega_efetivada_em', '<=', $date),
                            );
                    })
                    ->columnSpan(3),



            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(12)
            ->deferFilters(false)
            ->searchable(false)
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID);
            })
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
