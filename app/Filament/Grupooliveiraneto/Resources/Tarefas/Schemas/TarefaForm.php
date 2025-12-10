<?php

namespace App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas;

use App\Enums\TarefaStatus;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TarefaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::components());
    }

    public static function components(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Informações da tarefa
            |--------------------------------------------------------------------------
            */
            Section::make('')
                ->schema([

                    TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),


                    DateTimePicker::make('data_inicio')
                        ->label('Data de início')
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->required(),

                    DateTimePicker::make('data_fim')
                        ->label('Data limite')
                        ->required()
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->after('data_inicio'),

                    Select::make('status')
                        ->label('Status')
                        ->live()
                        ->options(TarefaStatus::values())
                        ->prefixIcon(fn ($state) => TarefaStatus::tryFrom($state)?->getIcon())
                        ->prefixIconColor(fn ($state) => TarefaStatus::tryFrom($state)?->getColor())
                        ->default(TarefaStatus::PENDENTE)
                        ->columnSpanFull(),

                    Select::make('responsaveis')
                        ->label('Responsáveis')
                        ->multiple()
                        ->options(User::all()->pluck('name', 'id')->toArray())
                        ->columnSpanFull(),

                    RichEditor::make('descricao')
                        ->label('Descrição')
                        ->columnSpanFull(),

                ])
                ->columns(2),


            /*
            |--------------------------------------------------------------------------
            | Recorrência (RRULE)
            |--------------------------------------------------------------------------
            */
            Section::make('Recorrência')
                ->description('Configure uma regra de repetição da tarefa semelhante ao Google Agenda')
                ->schema([

                    ToggleButtons::make('recorrencia_tem')
                        ->boolean()
                        ->inline()
                        ->default(false)
                        ->label('Esta tarefa se repete?')
                        ->reactive(),

                    Grid::make(3)
                        ->schema([

                            FusedGroup::make([
                                TextInput::make('recorrencia_intervalo')
                                    ->label('Repetir a cada')
                                    ->inlineLabel()
                                    ->prefixIcon('fas-retweet')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),

                                Select::make('recorrencia_frequencia')
                                    ->hiddenLabel(true)
                                    ->inlineLabel()
                                    ->options([
                                        'DAILY'   => 'Dia',
                                        'WEEKLY'  => 'Semana',
                                        'MONTHLY' => 'Mês',
                                        'YEARLY'  => 'Ano',
                                    ])
                                    ->reactive()
                                    ->required(),
                            ])
                                ->label('Repetir a cada')
                                ->columnSpanFull()
                                ->columns(2),


                        ])
                        ->columnSpanFull()
                        ->visible(fn (callable $get) => $get('recorrencia_tem')),

                    /*
                    |--------------------------------------------------------------------------
                    | BYDAY (somente para recorrência semanal)
                    |--------------------------------------------------------------------------
                    */
                    CheckboxList::make('recorrencia_dias_semana')
                        ->label('Dias da semana')
                        ->options([
                            'MO' => 'Segunda',
                            'TU' => 'Terça',
                            'WE' => 'Quarta',
                            'TH' => 'Quinta',
                            'FR' => 'Sexta',
                            'SA' => 'Sábado',
                            'SU' => 'Domingo',
                        ])
                        ->visible(fn (callable $get) =>
                            $get('recorrencia_frequencia') === 'WEEKLY'
                        )
                        ->columns(7),

                    /*
                    |--------------------------------------------------------------------------
                    | Término da recorrência (Google Agenda)
                    |--------------------------------------------------------------------------
                    */
                    Radio::make('recorrencia_tipo_fim')
                        ->label('Termina em')
                        ->options([
                            //'nunca' => 'Nunca',
                            'em'    => 'Em (data específica)',
                            'apos'  => 'Após X ocorrências',
                        ])
                        ->default('nunca')
                        ->reactive()
                        ->required(function (){

                        })
                        ->visible(fn (callable $get) => $get('recorrencia_tem')),

                    Grid::make(2)
                        ->schema([

                            // UNTIL
                            DateTimePicker::make('recorrencia_data_fim')
                                ->label('Data final da recorrência')
                                ->visible(fn (callable $get) => $get('recorrencia_tipo_fim') === 'em')
                                ->required(fn (callable $get) => $get('recorrencia_tipo_fim') === 'em'),

                            // COUNT
                            TextInput::make('recorrencia_quantidade_ocorrencias')
                                ->label('Número de ocorrências')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn (callable $get) => $get('recorrencia_tipo_fim') === 'apos')
                                ->required(fn (callable $get) => $get('recorrencia_tipo_fim') === 'apos'),
                        ])
                        ->visible(fn (callable $get) => $get('recorrencia_tem')),

                ])
                ->columns(1),

        ];
    }
}
