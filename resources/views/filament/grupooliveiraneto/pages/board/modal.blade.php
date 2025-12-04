<x-filament::modal id="editar-ocorrencia" width="5xl">

    <x-slot name="header">
        <x-filament::button wire:click="save">Salvar</x-filament::button>
    </x-slot>

    {{ $this->form }}

    <x-slot name="footer">
        <x-filament::button wire:click="save">Salvar</x-filament::button>
    </x-slot>

</x-filament::modal>
