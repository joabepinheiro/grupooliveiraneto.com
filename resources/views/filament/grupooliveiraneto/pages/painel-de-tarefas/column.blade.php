<div class="ring-gray-950/5">
    {{-- Header --}}
    <div class="pb-3 border-b-2 border-b-{{ $color }}-500 mb-4">
        <div class="flex items-center">
            <span
                class="
                    inline-flex items-center justify-center
                    w-[25px] h-[25px] text-sm font-semibold rounded-sm
                    text-white bg-{{ $color }}-500
                    shadow-sm shadow-gray-900/5  mx-2
                "
            >
                {{ $count }}
            </span>

            <h3 class="text-base font-semibold text-gray-900  uppercase">
                {{ $title }}
            </h3>


        </div>
    </div>

    {{-- Conteúdo --}}
    <div
        class="space-y-1 grid grid-cols-1 md:grid-cols-4 gap-2"
        data-task-dropzone="1"
        data-status="{{ $status }}"
    >
        @foreach($items as $ocorrencia)
            <div
                data-task-card="1"
                data-ocorrencia-id="{{ $ocorrencia->id }}"
            >
                @include(
                    'filament.grupooliveiraneto.pages.painel-de-tarefas.card',
                    ['ocorrencia' => $ocorrencia, 'color' => $color]
                )
            </div>
        @endforeach
    </div>
</div>
