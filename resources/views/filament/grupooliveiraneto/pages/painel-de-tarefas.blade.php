<x-filament-panels::page>

    {{-- Adicione este bloco de estilo para ocultar a sidebar e ajustar a margem --}}
    <style>

        .fi-body{
            background: var(--color-gray-100);
        }
        .fi-sidebar,
        .fi-global-search-ctn,
        .fi-icon-btn {
            display: none !important;
        }
        .fi-main {
            padding-inline-start: 30px !important;
        }

        #filtro .fi-fo-field-label-content{

        }

        #filtro .fi-select-input,
        #filtro .fi-input{
            font-weight: 600;
        }
    </style>

    <div class="space-y-6">

        {{-- Filtros --}}
        @include('filament.grupooliveiraneto.pages.painel-de-tarefas.filters')

        {{-- Board --}}
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6" data-task-board="1">

            {{-- Coluna Pendente --}}
            @include('filament.grupooliveiraneto.pages.painel-de-tarefas.column', [
                'title' => 'Tarefas pendentes',
                'count' => count($pendentes),
                'color' => 'yellow',
                'status' => 'Pendente',
                'items' => $pendentes,
            ])

            {{-- Coluna Em Andamento --}}
            @include('filament.grupooliveiraneto.pages.painel-de-tarefas.column', [
                'title' => 'Tarefas em andamento',
                'count' => count($em_andamento),
                'color' => 'blue',
                'status' => 'Em andamento',
                'items' => $em_andamento,
            ])

            {{-- Coluna Concluído --}}
            @include('filament.grupooliveiraneto.pages.painel-de-tarefas.column', [
                'title' => 'Tarefas concluídas',
                'count' => count($concluidos),
                'color' => 'green',
                'status' => 'Concluído',
                'items' => $concluidos,
            ])

        </div>
    </div>

    {{-- Modal --}}
    @include('filament.grupooliveiraneto.pages.painel-de-tarefas.modal')

</x-filament-panels::page>
