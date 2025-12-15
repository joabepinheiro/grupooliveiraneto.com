<?php

namespace App\Filament\Grupooliveiraneto\Pages;

use App\Enums\OcorrenciaDepartamentos;
use App\Enums\TarefaStatus;
use App\Filament\Grupooliveiraneto\Resources\Tarefas\Schemas\TarefaForm;
use App\Models\Tarefa\Ocorrencia;
use App\Models\Tarefa\Tarefa;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use UnitEnum;

class PainelDeTarefas extends Page implements HasForms
{
    use InteractsWithForms;
    use HasFiltersForm;

    protected static string|null|\BackedEnum $navigationIcon = 'fab-trello';

    protected string $view = 'filament.grupooliveiraneto.pages.painel-de-tarefas';

    protected static ?string $navigationLabel = 'Painel de tarefas';
    protected static ?string $title = 'Painel de tarefas';

    protected static ?string $slug = 'painel-de-tarefas';

    protected static string|UnitEnum|null $navigationGroup = 'Tarefas';

    /** Cache simples de opções de usuários (evita múltiplas queries por request) */
    protected static ?array $cachedUserOptions = null;

    /** Modelo sendo editado */
    public ?Ocorrencia $ocorrencia = null;

    /** Estado do formulário (ESSENCIAL) */
    public array $data = [];

    // ... existing code ...

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('Criar tarefa')
                ->schema(TarefaForm::components())
                ->using(function (array $data): Tarefa {
                    return Tarefa::create($data);
                }),
        ];
    }

    public function mount(): void
    {
        Filament::getCurrentPanel()->darkMode(false);

        $this->form->fill([
            'descricao' => '',
        ]);

        $this->filters = [
            'search_title' => '',
            'filter_responsavel' => null,
            'filter_data_fim_de' => today()->startOfWeek(),
            'filter_data_fim_ate' => today()->endOfWeek(),
            'filter_mes' => today()->format('m'),
            'filter_ano' => today()->format('Y'),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getUserOptions(): array
    {
        if (static::$cachedUserOptions !== null) {
            return static::$cachedUserOptions;
        }

        return static::$cachedUserOptions = User::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public function editOcorrencia(Ocorrencia $ocorrencia)
    {
        $responsaveis = $ocorrencia->responsaveis ?? [];

        if (! in_array(auth()->id(), $responsaveis) && $ocorrencia->created_by != auth()->id()) {
            Notification::make()
                ->title('Acesso negado')
                ->body('Você só pode editar tarefas das quais é responsável.')
                ->danger()
                ->send();

            return;
        }

        $this->ocorrencia = $ocorrencia;

        $this->form->fill($ocorrencia->toArray());

        $this->dispatch('open-modal', id: 'editar-ocorrencia');
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->live()
                        ->options(TarefaStatus::values())
                        ->prefixIcon(fn ($state) => TarefaStatus::tryFrom($state)?->getIcon())
                        ->prefixIconColor(fn ($state) => TarefaStatus::tryFrom($state)?->getColor())
                        ->default('pendente')
                        ->columnSpanFull(),

                    TextInput::make('titulo')
                        ->label('Título')
                        ->required()
                        ->extraAttributes(['class' => 'font-semibold'])
                        ->columnSpanFull()
                        ->maxLength(255),

                    DateTimePicker::make('data_inicio')
                        ->label('Data e hora de início')
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->required()
                        ->columnSpan([
                            'lg' => 6,
                        ]),

                    DateTimePicker::make('data_fim')
                        ->label('Data e hora de término')
                        ->required()
                        ->seconds(false)
                        ->prefixIcon('fas-calendar-days')
                        ->after('data_inicio')
                        ->columnSpan([
                            'lg' => 6,
                        ]),

                    Select::make('responsaveis')
                        ->label('Responsáveis')
                        ->multiple()
                        ->extraAttributes(['class' => 'font-semibold'])
                        ->options(fn (): array => $this->getUserOptions())
                        ->columnSpanFull(),


                    Select::make('departamentos')
                        ->label('Departamentos')
                        ->multiple()
                        ->extraAttributes(['class' => 'font-semibold'])
                        ->options(OcorrenciaDepartamentos::values())
                        ->columnSpanFull(),

                    RichEditor::make('descricao')
                        ->label('Descrição')
                        ->columnSpanFull(),

                    TextInput::make('id')
                        ->label('ID')
                        ->readOnly()
                        ->disabled()
                        ->columnSpan([
                            'lg' => 4,
                        ]),

                    DateTimePicker::make('created_at')
                        ->label('Cadastrado em')
                        ->readOnly()
                        ->disabled()
                        ->columnSpan([
                            'lg' => 4,
                        ]),

                    TextInput::make('created_by')
                        ->label('Cadastrado por')
                        ->readOnly()
                        ->disabled()
                        ->formatStateUsing(function ($state) {
                            $users = $this->getUserOptions();
                            return $users[(int) $state] ?? 'Não infomado';
                        })
                        ->columnSpan([
                            'lg' => 4,
                        ]),
                ])
                ->columns(12),
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('')
                ->contained(false)
                ->schema([
                    TextInput::make('search_id')
                        ->label('ID')
                        ->hiddenLabel(false)
                        ->placeholder('ID')
                        ->debounce(500)
                        ->numeric()
                        ->columnSpan([
                            'lg' => 3,
                        ]),

                    TextInput::make('search_title')
                        ->label('Título')
                        ->placeholder('Título')
                        ->hiddenLabel(false)
                        ->placeholder('Ex: Reunião')
                        ->debounce(500)
                        ->columnSpan([
                            'lg' => 5,
                        ]),

                    Select::make('filter_responsavel')
                        ->label('Responsável')
                        ->placeholder('Todos os responsável')
                        ->hiddenLabel(false)
                        ->options(fn (): array => $this->getUserOptions())
                        ->searchable()
                        ->columnSpan([
                            'lg' => 4,
                        ]),

                    Select::make('filter_departamentos')
                        ->label('Departamentos')
                        ->placeholder('Todos os departamentos')
                        ->hiddenLabel(false)
                        ->options(OcorrenciaDepartamentos::values())
                        ->columnSpan([
                            'lg' => 3,
                        ]),

                    FusedGroup::make([
                        DatePicker::make('filter_data_fim_de')
                            ->prefix('De'),

                        DatePicker::make('filter_data_fim_ate')
                            ->prefix('Até'),
                    ])
                        ->label('Prazo')
                        ->hiddenLabel(false)
                        ->columns(2)
                        ->columnSpan([
                            'lg' => 9,
                        ]),
                ])
                ->columnSpanFull()
                ->columns(12),
        ]);
    }

    public function save()
    {
        if ($this->ocorrencia) {
            $responsaveis = $this->ocorrencia->responsaveis ?? [];

            if (! in_array(auth()->id(), $responsaveis) && $this->ocorrencia->created_by !== auth()->id()) {
                Notification::make()
                    ->title('Ação não autorizada')
                    ->body('Você não tem permissão para salvar alterações nesta tarefa.')
                    ->danger()
                    ->send();

                return;
            }

            $this->ocorrencia->update($this->form->getState());
        }

        $this->dispatch('close-modal', id: 'editar-ocorrencia');
        $this->dispatch('$refresh');
    }

    // ... existing code ...

    protected function getViewData(): array
    {
        $baseQuery = Ocorrencia::query()
            ->select([
                'id',
                'agenda',
                'tarefa_id',
                'titulo',
                'status',
                'responsaveis',
                'departamentos',
                'data_inicio',
                'data_fim',
                'created_by',
                'created_at',
            ])
            ->with([
                'tarefa:id,recorrencia_tem,rrule',
            ]);

        $baseQuery->where('agenda', '=', 'grupooliveiraneto');

        if (! empty($this->filters['search_id'])) {
            $baseQuery->where('ID', $this->filters['search_id']);
        }

        if (! empty($this->filters['search_title'])) {
            $baseQuery->where('titulo', 'like', '%' . $this->filters['search_title'] . '%');
        }

        if (! empty($this->filters['filter_responsavel'])) {
            $baseQuery->whereJsonContains('responsaveis', (int) $this->filters['filter_responsavel']);
        }

        if (! empty($this->filters['filter_departamentos'])) {
            $baseQuery->whereJsonContains('departamentos', $this->filters['filter_departamentos']);
        }

        if (! empty($this->filters['filter_data_fim_de'])) {
            $baseQuery->whereDate('data_fim', '>=', $this->filters['filter_data_fim_de']);
        }

        if (! empty($this->filters['filter_data_fim_ate'])) {
            $baseQuery->whereDate('data_fim', '<=', $this->filters['filter_data_fim_ate']);
        }

        $allOcorrencias = $baseQuery->orderBy('data_fim', 'ASC')->get();

        // Pré-calcula os nomes de responsáveis 1x por ocorrência (evita trabalho repetido no Blade)
        $users = collect($this->getUserOptions());

        $allOcorrencias->each(function (Ocorrencia $ocorrencia) use ($users): void {
            $ids = $ocorrencia->responsaveis ?? [];
            $ocorrencia->responsaveis_nomes = empty($ids)
                ? ''
                : $users->only($ids)->implode(', ');
        });

        $grouped = $allOcorrencias->groupBy('status');

        return [
            'users' => $users,
            'pendentes' => $grouped->get('Pendente', collect()),
            'em_andamento' => $grouped->get('Em andamento', collect()),
            'concluidos' => $grouped->get('Concluído', collect()),
        ];
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

                if (! $this->ocorrencia->tarefa?->rrule) {
                    $scope = 'single';
                }

                switch ($scope) {
                    case 'single':
                        $this->ocorrencia->delete();
                        break;

                    case 'following':
                        Ocorrencia::where('tarefa_id', $this->ocorrencia->tarefa_id)
                            ->where('data_inicio', '>=', $this->ocorrencia->data_inicio)
                            ->delete();
                        break;

                    case 'all':
                        Ocorrencia::where('tarefa_id', $this->ocorrencia->tarefa_id)->delete();
                        Tarefa::where('id', $this->ocorrencia->tarefa_id)->delete();
                        break;
                }

                $this->dispatch('close-modal', id: 'editar-ocorrencia');
                $this->dispatch('$refresh');
            });
    }

    /**
     * Expõe a Action como "property" ($this->deleteAction) para o Blade/Livewire.
     */
    public function getDeleteActionProperty(): Action
    {
        return $this->deleteAction();
    }

    public function moveOcorrencia(int $ocorrenciaId, string $toStatus): void
    {
        $allowed = ['Pendente', 'Em andamento', 'Concluído'];

        if (! in_array($toStatus, $allowed, true)) {
            Notification::make()
                ->title('Status inválido')
                ->danger()
                ->send();

            return;
        }

        $ocorrencia = Ocorrencia::query()->find($ocorrenciaId);

        if (! $ocorrencia) {
            Notification::make()
                ->title('Ocorrência não encontrada')
                ->danger()
                ->send();

            return;
        }

        $responsaveis = $ocorrencia->responsaveis ?? [];

        if (! in_array(auth()->id(), $responsaveis) && $ocorrencia->created_by != auth()->id()) {
            Notification::make()
                ->title('Ação não autorizada')
                ->body('Você não tem permissão para mover esta tarefa.')
                ->danger()
                ->send();

            return;
        }

        $ocorrencia->update([
            'status' => $toStatus,
        ]);

        Notification::make()
            ->title('Tarefa movida')
            ->body("Novo status: {$toStatus}")
            ->success()
            ->send();
    }

    protected function getFormModel(): string|null
    {
        return null;
    }
}
