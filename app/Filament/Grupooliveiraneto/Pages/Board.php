<?php

namespace App\Filament\Grupooliveiraneto\Pages;

use App\Enums\TarefaStatus;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Models\Tarefa\Ocorrencia;
use App\Models\Tarefa\Tarefa;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

// Adicionado o HasFiltersForm ao use
class Board extends Page implements HasForms
{
    use InteractsWithForms;
    use HasFiltersForm; // 1. Adiciona o trait para habilitar o formulário de filtros

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-rectangle-group';

    protected string $view = 'filament.grupooliveiraneto.pages.board';

    protected static ?string $navigationLabel = 'Board';
    protected static ?string $title = 'Board de Tarefas';

    /** Modelo sendo editado */
    public ?Ocorrencia $ocorrencia = null;

    /** Estado do formulário (ESSENCIAL) */
    public array $data = [];

    // As propriedades dos filtros são armazenadas na propriedade pública $filters,
    // que é injetada pelo HasFiltersForm.



    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('')
                ->schema(TarefaForm::components())
                ->using(function (array $data): Tarefa {
                    $data['rrule'] = TarefaForm::formatarRrule($data);
                    return Tarefa::create($data);
                })
        ];
    }

    public function mount(): void
    {

        // Preenche o form state
        $this->form->fill([
            'descricao' => '',
        ]);

        $this->filters = [
            'search_title' => '',
            'filter_data_fim_de'    => today()->addDay(-30),
            'filter_data_fim_ate'   => today()->addDays(30),
            'filter_mes'            => today()->format('m'),
            'filter_ano'            => today()->format('Y'),
        ];
    }

    /** Define onde o formulário armazena os valores */
    protected function getFormStatePath(): ?string
    {
        return 'data'; // ← Sem isso, NUNCA preenche o formulário
    }

    public function editOcorrencia(Ocorrencia $ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;

        // Preenche os campos do formulário
        $this->form->fill($ocorrencia->toArray());

        // Abre o modal
        $this->dispatch('open-modal', id: 'editar-ocorrencia');
    }


    protected function getFormSchema(): array
    {
        return [
            Section::make('')
                ->schema([

                    TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),


                    DateTimePicker::make('data_inicio')
                        ->label('Data e hora de início')
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->required()
                        ->columnSpan([
                            'lg' => 6
                        ]),


                    DateTimePicker::make('data_fim')
                        ->label('Data e hora de término')
                        ->required()
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->after('data_inicio')
                        ->columnSpan([
                            'lg' => 6
                        ]),

                    Select::make('status')
                        ->label('Status')
                        ->live()
                        ->options(TarefaStatus::values())
                        ->prefixIcon(fn ($state) => TarefaStatus::tryFrom($state)?->getIcon())
                        ->prefixIconColor(fn ($state) => TarefaStatus::tryFrom($state)?->getColor())
                        ->default('pendente')
                        ->columnSpanFull(),

                    Select::make('responsaveis')
                        ->label('Responsáveis')
                        ->multiple()
                        ->options(User::all()->pluck('name', 'id')->toArray())
                        ->columnSpanFull(),

                    RichEditor::make('descricao')
                        ->label('Descrição')
                        ->columnSpanFull(),

                    DateTimePicker::make('created_at')
                        ->label('Cadastrado em')
                        ->readOnly()
                        ->disabled()
                        ->columnSpan([
                            'lg' => 6
                        ]),

                    TextInput::make('created_by')
                        ->label('Cadastrado por')
                        ->readOnly()
                        ->disabled()
                        ->formatStateUsing(function ($state){
                            return User::find($state)->name ?? 'Não infomado';
                        })
                        ->columnSpan([
                            'lg' => 6
                        ]),
                ])
                ->columns(12),
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('')
                ->schema([

                    TextInput::make('search_id')
                        ->label('Filtrar por ID')
                        ->debounce(500)
                        ->columnSpan([
                            'lg' => 2
                        ]),

                    TextInput::make('search_title')
                        ->label('Filtrar por título')
                        ->placeholder('Ex: Reunião')
                        ->debounce(500)
                        ->columnSpan([
                            'lg' => 3
                        ]),


                    FusedGroup::make([
                        DatePicker::make('filter_data_fim_de')
                            ->prefix('De'),

                        DatePicker::make('filter_data_fim_ate')
                            ->prefix('Até'),
                    ])
                        ->label('Filtrar por Prazo')
                        ->columns(2)
                        ->columnSpan([
                            'lg' => 7
                        ]),
                ])
                ->columnSpanFull()
                ->columns(12)

        ]);
    }


    public function save()
    {
        if ($this->ocorrencia) {

            $this->ocorrencia->update($this->form->getState());
        }

        $this->dispatch('close-modal', id: 'editar-ocorrencia');

        // Atualiza a página
        $this->dispatch('$refresh');
    }

    /** Valores enviados para a view Blade */
    protected function getViewData(): array
    {
        // Cria a query base
        $baseQuery = Ocorrencia::query();

        // Aplica os filtros
        if (!empty($this->filters['search_id'])) {
            $baseQuery->where('ID', $this->filters['search_id']);
        }

        if (!empty($this->filters['search_title'])) {
            $baseQuery->where('titulo', 'like', '%' . $this->filters['search_title'] . '%');
        }

        if (!empty($this->filters['filter_data_fim_de'])) {
            $baseQuery->whereDate('data_fim', '>=', $this->filters['filter_data_fim_de']);
        }

        if (!empty($this->filters['filter_data_fim_ate'])) {
            $baseQuery->whereDate('data_fim', '<=', $this->filters['filter_data_fim_ate']);
        }

        // Executa a query uma única vez
        $allOcorrencias = $baseQuery->latest()->get();

        // Agrupa por status
        $grouped = $allOcorrencias->groupBy('status');

        return [
            'users' => User::pluck('name', 'id'), // pluck já executa direto sem carregar tudo
            'pendentes' => $grouped->get('Pendente', collect()),
            'em_andamento' => $grouped->get('Em andamento', collect()),
            'concluidos' => $grouped->get('Concluído', collect()),
        ];
    }


    protected function getFormModel(): string|null
    {
        return null; // necessário em custom pages
    }


}
