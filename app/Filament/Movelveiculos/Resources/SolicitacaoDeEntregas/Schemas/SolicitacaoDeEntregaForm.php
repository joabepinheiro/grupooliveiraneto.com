<?php

namespace App\Filament\Movelveiculos\Resources\SolicitacaoDeEntregas\Schemas;

use App\Models\Empresa;
use App\Models\Entrega\EntregaHorarioBloqueado;
use App\Models\Modelo;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SolicitacaoDeEntregaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Solicitação de entrega')
                    ->schema([

                        Select::make('tipo_venda')
                            ->label('Tipo de venda')
                            ->required()
                            ->options([
                                'Venda direta' => 'Venda direta',
                                'Estoque'       => 'Estoque'
                            ])
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        DateTimePicker::make('data_prevista')
                            ->label('Data prevista para entrega')
                            ->seconds(false)
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    if (empty($value)) {
                                        return;
                                    }

                                    // Converte o valor recebido (com "T") para Carbon
                                    $data = \Carbon\Carbon::parse($value);

                                    $bloqueado = EntregaHorarioBloqueado::where('start', '<=', $data)
                                        ->where('end', '>=', $data)
                                        ->exists();

                                    if ($bloqueado) {
                                        $fail('A data selecionada está em um horário bloqueado para entregas.');
                                    }
                                };
                            })
                            ->columnSpan([
                                'lg' => 4
                            ]),


                        TextInput::make('proposta')
                            ->label('Número da proposta')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextInput::make('cliente')
                            ->label('Cliente')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        Select::make('vendedor_id')
                            ->label('Vendedor')
                            ->required()
                            ->options(User::query()
                                ->with('roles')
                                ->whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'Vendedor');
                                })
                                ->get()
                                ->pluck('name', 'id')
                                ->toArray())
                            ->columnSpan([
                                'lg' => 6
                            ]),

                        Select::make('modelo')
                            ->label('Modelo')
                            ->required()
                            ->live()
                            ->options(
                                Modelo::where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
                                    ->pluck('nome', 'nome')
                            )
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Select::make('cor')
                            ->label('Cor')
                            ->options(function (Get $get) {
                                $modeloNome = $get('modelo');

                                if (! $modeloNome) {
                                    return [];
                                }

                                $modelo = Modelo::where('nome', $modeloNome)->first();

                                if (! $modelo || empty($modelo->cores)) {
                                    return [];
                                }

                                // Se cores for um array de strings
                                return collect($modelo->cores)
                                    ->mapWithKeys(fn ($cor) => [$cor => $cor])
                                    ->toArray();
                            })
                            ->columnSpan([
                                'lg' => 3
                            ]),

                        TextInput::make('chassi')
                            ->label('Chassi')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextInput::make('brinde')
                            ->label('Brinde')
                            ->maxLength(255)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Select::make('foi_solicitado_emplacamento')
                            ->label('Foi solicitado emplacamento ?')
                            ->boolean()
                            ->required()
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Select::make('foi_solicitado_acessorio')
                            ->label('Foi solicitado acessórios ?')
                            ->live()
                            ->boolean()
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        Repeater::make('acessorios_solicitados')
                            ->label('Acessórios foram solicitados')
                            ->visible(function (Get $get){
                                if($get('foi_solicitado_acessorio')){
                                    return true;
                                }
                                return false;
                            })
                            ->schema([
                                TextInput::make('acessorio_solicitado')
                                    ->hiddenLabel(true)
                                    ->placeholder('Descrição do acessório')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Adicionar acessório solicitado')
                            ->columnSpanFull(),

                    ])
                    ->columns(12),
            ]);
    }
}
