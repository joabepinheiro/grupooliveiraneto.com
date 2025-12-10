<?php

namespace App\Filament\Grupooliveiraneto\Pages;

use App\Enums\TarefaStatus;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Models\Tarefa\Ocorrencia;
use App\Models\Tarefa\Tarefa;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
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
use UnitEnum;

// Adicionado o HasFiltersForm ao use
class PainelDeTarefas extends Page implements HasForms
{
    use InteractsWithForms;
    use HasFiltersForm; // 1. Adiciona o trait para habilitar o formulário de filtros

    protected static string|null|\BackedEnum $navigationIcon = 'fab-trello';

    protected string $view = 'filament.grupooliveiraneto.pages.painel-de-tarefas';

    protected static ?string $navigationLabel = 'Painel de tarefas';
    protected static ?string $title = 'Painel de tarefas';

    protected static ?string $slug = 'painel-de-tarefas';

    protected static string | UnitEnum | null $navigationGroup = 'Tarefas';


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
                ->modalHeading('Criar tarefa')
                ->schema(TarefaForm::components())
                ->using(function (array $data): Tarefa {
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
            'filter_responsavel'    => null,
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

                    TextInput::make('id')
                        ->label('ID')
                        ->readOnly()
                        ->disabled()
                        ->columnSpan([
                            'lg' => 4
                        ]),

                    DateTimePicker::make('created_at')
                        ->label('Cadastrado em')
                        ->readOnly()
                        ->disabled()
                        ->columnSpan([
                            'lg' => 4
                        ]),

                    TextInput::make('created_by')
                        ->label('Cadastrado por')
                        ->readOnly()
                        ->disabled()
                        ->formatStateUsing(function ($state){
                            return User::find($state)->name ?? 'Não infomado';
                        })
                        ->columnSpan([
                            'lg' => 4
                        ]),


                ])
                ->columns(12),
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Filtro de tarefas')
                ->schema([

                    TextInput::make('search_id')
                        ->label('ID')
                        ->debounce(500)
                        ->numeric()
                        ->columnSpan([
                            'lg' => 1
                        ]),

                    TextInput::make('search_title')
                        ->label('Título')
                        ->placeholder('Ex: Reunião')
                        ->debounce(500)
                        ->columnSpan([
                            'lg' => 3
                        ]),

                    Select::make('filter_responsavel')
                        ->label('Responsável')
                        ->options(User::pluck('name', 'id'))
                        ->searchable()
                        ->columnSpan([
                            'lg' => 3
                        ]),

                    FusedGroup::make([
                        DatePicker::make('filter_data_fim_de')
                            ->prefix('De'),

                        DatePicker::make('filter_data_fim_ate')
                            ->prefix('Até'),
                    ])
                        ->label('Prazo')
                        ->columns(2)
                        ->columnSpan([
                            'lg' => 5
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

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Excluir')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Excluir Ocorrência')
            ->modalDescription('Como você deseja excluir esta ocorrência?')
            ->schema(function () {
                // Se não for recorrente, não mostra opções, apenas confirmação padrão
                if (! $this->ocorrencia?->tarefa?->rrule) {
                    return [];
                }

                return [
                    Radio::make('scope')
                        ->label('')
                        ->options([
                            'single'    => 'Esta ocorrência',
                            'following' => 'Esta e as ocorrências seguintes',
                            'all'       => 'Todas as ocorrências',
                        ])
                        ->default('single')
                        ->required(),
                ];
            })
            ->action(function (array $data) {
                if (! $this->ocorrencia) {
                    return;
                }

                $scope = $data['scope'] ?? 'single';

                // Se não for recorrente, força exclusão única
                if (! $this->ocorrencia->tarefa?->rrule) {
                    $scope = 'single';
                }

                switch ($scope) {
                    case 'single':
                        // Exclui apenas esta ocorrência
                        $this->ocorrencia->delete();
                        break;

                    case 'following':
                        // Exclui esta e as futuras da mesma tarefa
                        Ocorrencia::where('tarefa_id', $this->ocorrencia->tarefa_id)
                            ->where('data_inicio', '>=', $this->ocorrencia->data_inicio)
                            ->delete();
                        break;

                    case 'all':

                        Ocorrencia::where('tarefa_id', $this->ocorrencia->tarefa_id)
                            ->delete();

                        Tarefa::where('id', $this->ocorrencia->tarefa_id)
                            ->delete();

                        break;
                }

                $this->dispatch('close-modal', id: 'editar-ocorrencia');
                $this->dispatch('$refresh');
            });
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

        if (!empty($this->filters['filter_responsavel'])) {

            $baseQuery->whereJsonContains('responsaveis', (int) $this->filters['filter_responsavel']);
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
