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
            Section::make('Informações da Tarefa')
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
                        ->default(TarefaStatus::PENDENTE),

                    Select::make('responsaveis')
                        ->label('Responsáveis')
                        ->multiple()
                        ->options(User::all()->pluck('name', 'id')->toArray()),

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

                    ToggleButtons::make('tem_recorrencia')
                        ->boolean()
                        ->inline()
                        ->default(false)
                        ->label('Esta tarefa se repete?')
                        ->reactive(),

                    Grid::make(3)
                        ->schema([

                            FusedGroup::make([
                                TextInput::make('intervalo')
                                    ->label('Repetir a cada')
                                    ->inlineLabel()
                                    ->prefixIcon('fas-retweet')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                                Select::make('frequencia')
                                    ->hiddenLabel(true)
                                    ->inlineLabel()
                                    ->options([
                                        'DAILY'   => 'Dia',
                                        'WEEKLY'  => 'Semana',
                                        'MONTHLY' => 'Mês',
                                        'YEARLY'  => 'Ano',
                                        'CUSTOM'  => 'Personalizado (RRULE manual)',
                                    ])
                                    ->reactive()
                                    ->required(),
                            ])
                                ->label('Repetir a cada')
                                ->columns(2),



                            // Apenas quando CUSTOM
                            TextInput::make('rrule_manual')
                                ->label('RRULE Manual')
                                ->placeholder('Ex.: FREQ=DAILY;INTERVAL=2')
                                ->visible(fn (callable $get) =>
                                    $get('frequencia') === 'CUSTOM'
                                ),
                        ])
                        ->columnSpanFull()
                        ->visible(fn (callable $get) => $get('tem_recorrencia')),

                    /*
                    |--------------------------------------------------------------------------
                    | BYDAY (somente para recorrência semanal)
                    |--------------------------------------------------------------------------
                    */
                    CheckboxList::make('dias_semana')
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
                            $get('frequencia') === 'WEEKLY'
                        )
                        ->columns(7),

                    /*
                    |--------------------------------------------------------------------------
                    | Término da recorrência (Google Agenda)
                    |--------------------------------------------------------------------------
                    */
                    Radio::make('tipo_fim')
                        ->label('Termina em')
                        ->options([
                            'nunca' => 'Nunca',
                            'em'    => 'Em (data específica)',
                            'apos'  => 'Após X ocorrências',
                        ])
                        ->default('nunca')
                        ->reactive()
                        ->required(function (){

                        })
                        ->visible(fn (callable $get) => $get('tem_recorrencia')),

                    Grid::make(2)
                        ->schema([

                            // UNTIL
                            DateTimePicker::make('data_fim_recorrencia')
                                ->label('Data final da recorrência')
                                ->visible(fn (callable $get) => $get('tipo_fim') === 'em')
                                ->required(fn (callable $get) => $get('tipo_fim') === 'em'),

                            // COUNT
                            TextInput::make('quantidade_ocorrencias')
                                ->label('Número de ocorrências')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn (callable $get) => $get('tipo_fim') === 'apos')
                                ->required(fn (callable $get) => $get('tipo_fim') === 'apos'),
                        ])
                        ->visible(fn (callable $get) => $get('tem_recorrencia')),

                ])
                ->columns(1),

        ];
    }

    public static function formatarRrule($data){
        /*
        |--------------------------------------------------------------------------
        | Se não é recorrente, limpa a RRULE
        |--------------------------------------------------------------------------
        */
        if (empty($data['tem_recorrencia'])) {
            $data['rrule'] = null;

            unset(
                $data['frequencia'],
                $data['intervalo'],
                $data['dias_semana'],
                $data['rrule_manual'],
                $data['tipo_fim'],
                $data['data_fim_recorrencia'],
                $data['quantidade_ocorrencias']
            );

            return $data;
        }


        /*
        |--------------------------------------------------------------------------
        | Monta RRULE personalizada (CUSTOM)
        |--------------------------------------------------------------------------
        */
        if ($data['frequencia'] === 'CUSTOM') {
            $data['rrule'] = $data['rrule_manual'];

        } else {

            /*
            |--------------------------------------------------------------------------
            | Montagem padrão de RRULE (DAILY, WEEKLY, MONTHLY, YEARLY)
            |--------------------------------------------------------------------------
            */
            $rrule = "FREQ={$data['frequencia']};INTERVAL=" . ($data['intervalo'] ?? 1);

            // BYDAY (apenas semanal)
            if ($data['frequencia'] === 'WEEKLY' && !empty($data['dias_semana'])) {
                $rrule .= ";BYDAY=" . implode(',', $data['dias_semana']);
            }

            /*
            |--------------------------------------------------------------------------
            | Término da recorrência (tipo_fim)
            |--------------------------------------------------------------------------
            */
            if (!empty($data['tipo_fim'])) {
                switch ($data['tipo_fim']) {

                    case 'em': // UNTIL
                        if (!empty($data['data_fim_recorrencia'])) {
                            $until = \Carbon\Carbon::parse($data['data_fim_recorrencia'])->format('Ymd\T000000\Z');
                            $rrule .= ";UNTIL={$until}";
                        }
                        break;

                    case 'apos': // COUNT
                        if (!empty($data['quantidade_ocorrencias'])) {
                            $rrule .= ";COUNT=" . intval($data['quantidade_ocorrencias']);
                        }
                        break;

                    case 'nunca':
                    default:
                        // nada a adicionar
                        break;
                }
            }

            $data['rrule'] = $rrule;
        }


        /*
        |--------------------------------------------------------------------------
        | Remove campos auxiliares
        |--------------------------------------------------------------------------
        */
        unset(
            $data['tem_recorrencia'],
            $data['frequencia'],
            $data['intervalo'],
            $data['dias_semana'],
            $data['rrule_manual'],
            $data['tipo_fim'],
            $data['data_fim_recorrencia'],
            $data['quantidade_ocorrencias']
        );

        return $data['rrule'];
    }
}
