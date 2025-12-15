{{-- Safelist para classes dinâmicas
    bg-gray-600 bg-yellow-600 bg-blue-600 bg-green-600 bg-red-600
    bg-gray-500 bg-yellow-500 bg-blue-500 bg-green-500 bg-red-500
    bg-gray-200 bg-yellow-200 bg-blue-200 bg-green-200 bg-red-200
    bg-gray-100 bg-yellow-100 bg-blue-100 bg-green-100 bg-red-100
    bg-gray-50 bg-yellow-50 bg-blue-50 bg-green-50 bg-red-50

    text-gray-600 text-yellow-600 text-blue-600 text-green-600 text-red-600
    text-gray-900 text-yellow-900 text-blue-900 text-green-900 text-red-900

    border-gray-300 border-yellow-300 border-blue-300 border-green-300 border-red-300
    border-gray-100 border-yellow-100 border-blue-100 border-green-100 border-red-100
    border-l-gray-500 border-l-yellow-500 border-l-blue-500 border-l-green-500 border-l-red-500

    text-blue-900 text-yellow-900 text-green-900 text-gray-900 text-red-900

    border-b-blue-500 border-b-yellow-500 border-b-green-500 border-b-blue-500 border-b-gray-500 border-b-red-500
--}}

@php
    $isAtrasado = $ocorrencia->data_fim->isPast() && $ocorrencia->status !== 'Concluído';
@endphp

<div
    wire:click="editOcorrencia({{ $ocorrencia->id }})"
    class="
        relative
        p-4 rounded-lg cursor-pointer transition-shadow
        bg-white
        border border-gray-100
        border-l-4 border-l-{{ $color }}-500
        hover:shadow-md
    "
>


    {{-- Título --}}
    <h4 class="text-base font-semibold text-gray-800 py-0 mb-1">
        {{ $ocorrencia->titulo }}
    </h4>

    {{-- Responsáveis --}}
    @if(!empty($ocorrencia->responsaveis))
        <div class="text-xs font-semibold text-gray-600  pt-1 pb-1">
            {{ $ocorrencia->responsaveis_nomes ?? '' }}
        </div>
    @endif

    {{-- Departamentos --}}
    @if(!empty($ocorrencia->departamentos))
        <div class="text-xs font-normal text-gray-600  pt-1 pb-2">
            {{implode(', ', $ocorrencia->departamentos)}}
        </div>
    @endif





    {{-- Início --}}
    <div class="
        inline-flex items-center px-2 py-1 mb-1 text-sm font-semibold rounded-md
        bg-{{$color}}-100
        text-{{$color}}-900
        ring-gray-600/20
    ">
        De: {{ $ocorrencia->data_inicio->format('d/m - H:i') }}
    </div>

    {{-- Fim / Atrasado --}}
    <div class="
        inline-flex items-center px-2 py-1 mb-0 text-sm font-semibold rounded-md
        bg-{{$color}}-100
        text-{{$color}}-900
        ring-gray-600/20
    ">
        Até: {{ $ocorrencia->data_fim->format('d/m - H:i') }}

    </div>

    @if($isAtrasado)
        <div class="
        inline-flex items-center px-2 py-1 mb-0 text-sm font-semibold rounded-md
        bg-red-600
        text-white
        ring-gray-600/20
    ">
            Atrasado

        </div>
    @endif

    {{-- ID + Recorrência --}}
    <span class="absolute bottom-1 right-2 text-xs text-gray-600">
        @if($ocorrencia->tarefa->recorrencia_tem)
            <span title="Tarefa recorrente">*</span>
        @endif
        {{ $ocorrencia->id }}
    </span>

</div>
