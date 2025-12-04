<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">

    {{-- Header --}}
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <span class="inline-flex items-center justify-center  w-[30px] h-[30px] text-sm font-semibold text-white bg-{{ $color }}-500 rounded-sm">
                {{ $count }}
            </span>
        </div>
    </div>

    {{-- Cards --}}
    <div class="p-4 space-y-3">
        @foreach($items as $ocorrencia)
            @include('filament.grupooliveiraneto.pages.board.card', [
                'ocorrencia' => $ocorrencia,
                'color' => $color
            ])
        @endforeach
    </div>
</div>
