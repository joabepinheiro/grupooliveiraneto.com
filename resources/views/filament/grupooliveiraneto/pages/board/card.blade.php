{{--
    Safelist for dynamic colors:
    bg-yellow-500 bg-blue-500 bg-green-500
    text-yellow-600 text-blue-600 text-green-600
    border-yellow-200 border-blue-200 border-green-200

    border-l-yellow-500 border-l-blue-500 border-l-green-500
--}}

<div
    class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg hover:shadow-md transition-shadow cursor-pointer
           border-l-8 border-l-{{ $color }}-500 border border-gray-200 dark:border-gray-700"
    wire:click="editOcorrencia({{ $ocorrencia->id }})"
>
    <span class="text-xs font-medium text-gray-600 mb-3">ID: {{$ocorrencia->id}}</span>
    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">{{$ocorrencia->titulo}}</h4>

    <div class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-500/10 text-gray-800 dark:text-gray-400 ring-gray-600/20 dark:ring-gray-500/30  px-2 py-1 mb-2 text-xs font-medium  ring-1 ring-inset ">
        Início: {{$ocorrencia->data_inicio->format('d/m/y - h:i')}}
    </div>

    <br/>
    <div class="inline-flex items-center rounded-md {{$ocorrencia->data_fim->isPast() ? 'bg-red-50 dark:bg-red-500/10 text-red-800 dark:text-red-400 ring-red-600/20 dark:ring-red-500/30' : 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-800 dark:text-yellow-400 ring-yellow-600/20 dark:ring-yellow-500/30'}}  px-2 py-1 mb-2 text-xs font-medium  ring-1 ring-inset ">
        Fim: {{$ocorrencia->data_fim->format('d/m/y - h:i')}}

        @if($ocorrencia->data_fim->isPast())
            (atrazado)
        @endif
    </div>

    @if($ocorrencia->responsaveis)
        <div class="text-sm font-normal text-gray-800 pt-2 pb-3">
            <b>Responsáveis: </b>
            <br/>
            {{ collect($users)->only($ocorrencia->responsaveis ?? [])->implode(', ') }}
        </div>
    @endif
</div>
