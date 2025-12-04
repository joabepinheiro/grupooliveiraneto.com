<x-filament-panels::page>

    <div class="space-y-6">

        {{-- Filtros --}}
        @include('filament.grupooliveiraneto.pages.board.filters')

        {{-- Board --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Coluna Pendente --}}
            @include('filament.grupooliveiraneto.pages.board.column', [
                'title' => 'Tarefas pendentes',
                'count' => count($pendentes),
                'color' => 'yellow',
                'items' => $pendentes,
            ])

            {{-- Coluna Em Andamento --}}
            @include('filament.grupooliveiraneto.pages.board.column', [
                'title' => 'Tarefas em andamento',
                'count' => count($em_andamento),
                'color' => 'blue',
                'items' => $em_andamento,
            ])

            {{-- Coluna Concluído --}}
            @include('filament.grupooliveiraneto.pages.board.column', [
                'title' => 'Tarefas concluídas',
                'count' => count($concluidos),
                'color' => 'green',
                'items' => $concluidos,
            ])

        </div>
    </div>

    {{-- Modal --}}
    @include('filament.grupooliveiraneto.pages.board.modal')

</x-filament-panels::page>
