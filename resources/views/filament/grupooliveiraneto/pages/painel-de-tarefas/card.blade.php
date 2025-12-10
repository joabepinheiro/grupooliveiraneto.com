{{-- Safelist para classes dinâmicas
    bg-gray-500 bg-yellow-500 bg-blue-500 bg-green-500
    text-gray-600 text-yellow-600 text-blue-600 text-green-600
    border-gray-200 border-yellow-200 border-blue-200 border-green-200
    border-l-gray-500 border-l-yellow-500 border-l-blue-500 border-l-green-500
--}}

@php
    $isAtrasado = $ocorrencia->data_fim->isPast() && $ocorrencia->status !== 'Concluído';

    $fimClasses = $isAtrasado
        ? 'bg-red-50 dark:bg-red-500/10 text-red-800 dark:text-red-400 ring-red-600/20 dark:ring-red-500/30'
        : 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 ring-yellow-600/20 dark:ring-yellow-500/30';
@endphp

<div
    wire:click="editOcorrencia({{ $ocorrencia->id }})"
    class="
        p-4 rounded-lg cursor-pointer transition-shadow
        bg-gray-50 dark:bg-gray-900/50
        border border-gray-200 dark:border-gray-700
        border-l-8 border-l-{{ $color }}-500
        hover:shadow-md
    "
>
    {{-- ID + Recorrência --}}
    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-3 block">
        @if($ocorrencia->tarefa->recorrencia_tem)
            <span title="Tarefa recorrente">*</span>
        @endif

        ID: {{ $ocorrencia->id }}
    </span>

    {{-- Título --}}
    <h4 class="font-semibold text-gray-900 dark:text-white py-2">
        {{ $ocorrencia->titulo }}
    </h4>

    {{-- Início --}}
    <div class="
        inline-flex items-center px-3 py-1 mb-2 text-xs font-medium rounded-md ring-1 ring-inset
        bg-gray-100 dark:bg-gray-500/10
        text-gray-600 dark:text-gray-400
        ring-gray-600/20 dark:ring-gray-500/30
    ">
        Início: {{ $ocorrencia->data_inicio->format('d/m/y - H:i') }}
    </div>

    {{-- Fim / Atrasado --}}
    <div class="
        inline-flex items-center px-3 py-1 mb-2 text-xs font-medium rounded-md ring-1 ring-inset
        {{ $fimClasses }}
    ">
        Fim: {{ $ocorrencia->data_fim->format('d/m/y - H:i') }}

        @if($isAtrasado)
            (atrasado)
        @endif
    </div>

    {{-- Responsáveis --}}
    @if(!empty($ocorrencia->responsaveis))
        <div class="text-sm font-normal text-gray-800 dark:text-gray-300 pt-2 pb-3">
            <b class="font-semibold">Responsáveis:</b><br>
            {{ collect($users)->only($ocorrencia->responsaveis ?? [])->implode(', ') }}
        </div>
    @endif
</div>
