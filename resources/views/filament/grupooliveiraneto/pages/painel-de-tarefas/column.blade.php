<div
    class="
        rounded-lg shadow-sm
        bg-white dark:bg-gray-800
        ring-1 ring-gray-950/5 dark:ring-white/10
    "
>
    {{-- Header --}}
    <div
        class="
            p-4 rounded-t-lg border-b
            bg-gray-100 dark:bg-gray-700/30
            border-gray-200 dark:border-gray-700
        "
    >
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>

            <span
                class="
                    inline-flex items-center justify-center
                    w-[30px] h-[30px] text-sm font-semibold rounded-sm
                    text-white bg-{{ $color }}-500
                    shadow-sm shadow-gray-900/5 dark:shadow-none
                "
            >
                {{ $count }}
            </span>
        </div>
    </div>

    {{-- Conteúdo --}}
    <div class="p-4 space-y-3">
        @foreach($items as $ocorrencia)
            @include(
                'filament.grupooliveiraneto.pages.painel-de-tarefas.card',
                ['ocorrencia' => $ocorrencia, 'color' => $color]
            )
        @endforeach
    </div>
</div>
