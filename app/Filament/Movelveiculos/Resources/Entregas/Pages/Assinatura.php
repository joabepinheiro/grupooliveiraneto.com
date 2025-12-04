<?php

namespace App\Filament\Movelveiculos\Resources\Entregas\Pages;

use App\Filament\Forms\Components\SignaturePad;
use App\Filament\Movelveiculos\Resources\Entregas\EntregaResource;
use Carbon\Carbon;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Assinatura extends EditRecord
{
    protected static string $resource = EntregaResource::class;

    protected static ?string $title = 'Assinatura do cliente';

    protected static ?string $navigationLabel = 'Assinatura do cliente';



    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Section::make('Dados gerais da entrega')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->state(fn ($record): string|null => $record->status ?? null)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('data_prevista')
                            ->label('Data prevista para entrega')
                            ->state(fn ($record): string => $record->data_prevista ? Carbon::parse($record->data_prevista)->format('d/m/Y') : NULL)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('entrega_efetivada_em')
                            ->label('Entrega efetivada em')
                            ->state(fn ($record): string => $record->entrega_efetivada_em ? Carbon::parse($record->entrega_efetivada_em)->format('d/m/Y') : NULL)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('proposta')
                            ->label('Número da proposta')
                            ->state(fn ($record): string|null => $record->proposta  ?? null)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('cliente')
                            ->label('Cliente')
                            ->state(fn ($record): string|null => $record->cliente  ?? null)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('vendedor_id')
                            ->label('Vendedor')
                            ->state(fn ($record): string|null => $record->vendedor->name   ?? null)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('modelo')
                            ->label('Modelo')
                            ->state(fn ($record): string|null => $record->modelo  ?? null)
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('cor')
                            ->label('Cor')
                            ->state(fn ($record): string|null => $record->cor ?? null)
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        TextEntry::make('chassi')
                            ->label('Chassi')
                            ->state(fn ($record): string|null => $record->chassi ?? null)
                            ->placeholder('Não informado')
                            ->columnSpan([
                                'lg' => 4
                            ]),

                        RichEditor::make('observacoes')
                            ->label('Observações')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->visible(function ($state){
                                return !empty($state);
                            })
                            ->columnSpanFull(),

                    ])
                    ->disabled(function (){
                        if(auth()->user()->roles->contains('name', 'Entregador técnico')){
                            return false;
                        }
                        return true;
                    })
                    ->disabled(true)
                    ->columns(12),


                Section::make('Termo de entrega e aceite de veículo')
                    ->schema([

                        ViewField::make('termo-de-entrega-e-aceite-de-veiculo')
                            ->columnSpanFull()
                            ->view('filament.forms.components.termo-de-entrega-e-aceite-de-veiculo'),

                    ])->columnSpanFull(),

                Section::make('')
                    ->schema([
                        ToggleButtons::make('aceita_ativacao_conectividade_carro_conectado')
                            ->label(new HtmlString('<b>Carro Conectado</b> - O cliente aceitou realizar a rotina de ativação e adesão ao plano de conectividade:'))
                            ->required()
                            ->options([
                                'Sim'           =>'Sim',
                                'Não'           => 'Não',
                                'Não se aplica ao modelo/versão'         => 'Não se aplica ao modelo/versão',
                            ])
                            ->colors([
                                'Sim' => 'success',
                                'Não' => 'danger',
                                'Não se aplica ao modelo/versão' => 'gray',
                            ])
                            ->extraFieldWrapperAttributes(['class' => 'py-2'])
                            ->inline()
                            ->columnSpanFull(),


                        Toggle::make('cliente_se_recusou_a_receber_as_informacoes_contidas')
                            ->label('Cliente se recusou a receber as informações contidas neste formulário')
                            ->extraFieldWrapperAttributes(['class' => 'py-4'])
                            ->inline()
                            ->columnSpanFull(),

                    ]),

                Section::make('')
                    ->schema([
                        Toggle::make('aceite_termo')
                            ->label('Eu asseguro ter lido o Termo de Entrega e Aceite de Veículo e verificado a exatidão das informações dos itens mencionados acima.')
                            ->required()
                            ->columnSpanFull(),


                        SignaturePad::make('assinatura_do_cliente')
                            ->label('Assinatura do Responsável')
                            ->required(),

//
//                        SignaturePad::make('assinatura_do_cliente')
//                            ->label(__('Assinatura do cliente'))
//
//                            ->dotSize(2.0)
//                            ->lineMinWidth(0.5)
//                            ->lineMaxWidth(2.5)
//                            ->throttle(16)
//                            ->minDistance(5)
//                            ->velocityFilterWeight(0.7)
//                            ->columnSpanFull(),
                    ]),

            ])->columns(1);

    }
}
