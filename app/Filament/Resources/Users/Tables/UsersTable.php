<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('')
                    ->defaultImageUrl(url('/images/avatar.png'))
                    ->circular(),


                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($state){
                        if($state == 'Ativo')
                            return 'success';

                        if($state == 'Desativado')
                            return 'danger';

                        return 'success';
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Funções')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('empresas.nome')
                    ->label('Empresas')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('id')
                    ->schema([
                        TextInput::make('id')
                            ->label('Código')
                            ->placeholder('Código'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->whereLike('id', '%'.$data['id'].'%');
                    })
                    ->columnSpan(2),

                Filter::make('nome')
                    ->schema([
                        TextInput::make('nome')
                            ->label('Nome')
                            ->placeholder('Nome'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['nome'],
                                fn (Builder $query, $date): Builder => $query->where('nome', 'like', '%'.$date.'%')->orwhere('nome_fantasia', 'like', '%'.$date.'%'),
                            );
                    })->columnSpan(6),

                Filter::make('documentos')
                    ->schema([
                        TextInput::make('documento')
                            ->label('CPF/CNPJ')
                            ->placeholder('CPF/CNPJ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['documento'],
                                fn (Builder $query, $date): Builder => $query->where('cpf', 'like', '%'.$date.'%')->orwhere('cnpj', 'like', '%'.$date.'%'),
                            );
                    })->columnSpan(4),


                Filter::make('logradouro')
                    ->schema([
                        TextInput::make('logradouro')
                            ->label('Logradouro')
                            ->placeholder('Logradouro'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['logradouro'],
                                fn (Builder $query, $date): Builder => $query->withWhereHas('enderecos', function ($query) use ($date) {
                                    $query->where('logradouro', 'like', '%'.$date.'%');
                                }),
                            );
                    })->columnSpan(5),


                Filter::make('bairro')
                    ->schema([
                        TextInput::make('bairro')
                            ->label('Bairro')
                            ->placeholder('Bairro'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['bairro'],
                                fn (Builder $query, $date): Builder => $query->withWhereHas('enderecos', function ($query) use ($date) {
                                    $query->where('bairro', 'like', '%'.$date.'%');
                                }),
                            );
                    })->columnSpan(4),

                Filter::make('cep')
                    ->form([
                        TextInput::make('cep')
                            ->label('CEP')
                            ->placeholder('CEP'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cep'],
                                fn (Builder $query, $date): Builder => $query->withWhereHas('enderecos', function ($query) use ($date) {
                                    $query->where('cep', 'like', '%'.$date.'%');
                                }),
                            );
                    })->columnSpan(3),


                Filter::make('telefones')
                    ->schema([
                        TextInput::make('telefones')
                            ->label('Telefone')
                            ->placeholder('Telefone'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['telefones'],
                                fn (Builder $query, $date): Builder => $query->withWhereHas('telefones', function ($query) use ($date) {
                                    $query->where('numero', 'like', '%'.$date.'%');
                                }),
                            );
                    })->columnSpan(4),

                Filter::make('email')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Email'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['email'],
                                fn (Builder $query, $date): Builder => $query->where('email', 'like', '%'.$date.'%'),
                            );
                    })->columnSpan(5),

                TrashedFilter::make()->columnSpan(3),

                //Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(12)
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
