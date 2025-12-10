<?php

namespace App\Filament\Movelveiculos\Pages;


use App\Models\Empresa;
use App\Models\Entrega\EntregaHorarioBloqueado;
use App\Models\Modelo;
use App\Models\User;
use BackedEnum;
use Filament\Exceptions\NoDefaultPanelSetException;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\BasePage;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SolicitacaoDeEntrega extends BasePage implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.movelveiculos.pages.solicitacao-de-entrega';

    protected static string $layout = 'filament.movelveiculos.layout.base';

    public ?array $data = [];


    public function hasLogo(): bool
    {
        return true;
    }


    /**
     * @throws NoDefaultPanelSetException
     */
    public function form(Schema $schema): Schema
    {

        return $schema
            ->schema([
                Section::make('Solicitação de entrega')
                    ->schema([

                        Select::make('tipo_venda')
                            ->label('Tipo de venda')
                            ->required()
                            ->live()
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
                                ->with(['roles', 'empresas'])
                                ->whereHas('roles', function ($query) {
                                    $query->where('name', '=', 'Vendedor')
                                        ->where('panel_id', '=', 'movelveiculos');
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
                                Modelo::query()
                                    ->where('empresa_id', '=', Empresa::MOVEL_VEICULOS_ID)
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
                                'lg' => 4
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
                                    ->label('')
                                    ->placeholder('Descrição do acessório')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Adicionar acessório solicitado')
                            ->columnSpanFull(),

                    ])
                    ->statePath('data')
                    ->columns(12),

            ]);
    }

    public function submit()
    {
        $state = $this->form->getState();
        $state['data']['empresa_id'] = Empresa::MOVEL_VEICULOS_ID;

        \App\Models\Entrega\SolicitacaoDeEntrega::create($state['data']);

        Notification::make()
            ->title('Solicitação de entrega enviada')
            ->success()
            ->send();

        $this->redirect('/movelveiculos/solicitacao-de-entrega');
    }
}
