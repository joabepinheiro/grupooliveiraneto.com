<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    @php
        $state = $getState();

        // Pega somente old/new (com fallback comum do pacote)
        $old = [];
        $new = [];

        if (is_array($state)) {
            $old = $state['old'] ?? $state['old_attributes'] ?? [];
            $new = $state['new'] ?? $state['attributes'] ?? [];
        }

        $old = is_array($old) ? $old : [];
        $new = is_array($new) ? $new : [];

        // Campos que não devem aparecer
        $excludedKeys = ['deleted_at', 'deleted_by'];

        // Junta todas as chaves para montar a tabela
        $keys = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));

        // Remove os campos excluídos
        $keys = array_values(array_diff($keys, $excludedKeys));

        //sort($keys);

        $formatCell = function ($value) {
            if (is_null($value)) {
                return '';
            }

            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }

            if (is_scalar($value)) {
                $string = (string) $value;
                return $string === '' ? '—' : $string;
            }

            // arrays/objetos: mostra JSON (compacto) para caber bem em célula
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        };
    @endphp

    <div {{ $getExtraAttributeBag() }} class="w-full">
        @if(empty($keys))
            <div class="text-sm text-gray-500">
                Sem alterações para exibir.
            </div>
        @else
            <div class="w-full overflow-x-auto rounded-lg border border-gray-300 bg-white">
                <table class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">
                            Campo
                        </th>
                        <th class="px-3 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">
                            old
                        </th>
                        <th class="px-3 py-4 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">
                            new
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                    @foreach ($keys as $key)
                        @php
                            $oldValue = $old[$key] ?? null;
                            $newValue = $new[$key] ?? null;

                            $oldText = $formatCell($oldValue);
                            $newText = $formatCell($newValue);

                            $striped = $loop->even ? 'bg-gray-50' : 'bg-white';
                        @endphp

                        <tr class="{{ $striped }}">
                            <td class="px-3 py-3 align-top text-sm font-medium text-gray-900 break-words">
                                {{ __('audit.fields.'.$key) }}
                            </td>

                            <td class="px-3 py-3 align-top text-sm text-gray-900">
                                <div class="break-words leading-4">
                                    {{ $oldText }}
                                </div>
                            </td>

                            <td class="px-3 py-3 align-top text-sm text-gray-900">
                                <div class="break-words  leading-4">
                                    {{ $newText }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-2 text-xs text-gray-500">
                Linhas destacadas indicam valores diferentes entre <span class="font-medium">old</span> e <span class="font-medium">new</span>.
            </div>
        @endif
    </div>
</x-dynamic-component>
