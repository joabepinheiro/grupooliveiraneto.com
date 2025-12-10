<x-filament::modal id="editar-ocorrencia" width="5xl">

    <x-slot name="header">

    </x-slot>

    {{ $this->form }}

    <x-slot name="footer">
        <div class="flex justify-between w-full items-center">
            <div>
                <x-filament::button wire:click="save">Salvar</x-filament::button>
            </div>

            <div>
                {{ $this->deleteAction }}
            </div>
        </div>
    </x-slot>

</x-filament::modal>
