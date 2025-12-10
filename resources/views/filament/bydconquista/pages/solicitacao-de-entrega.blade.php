<div>
    <div class="flex justify-center items-center py-3" style="background: #252728 !important;">
        <img  src="{{asset('images/bydconquista/logo-vitoria-da-conquista.png')}}" style="height: 2.3rem;" class="fi-logo flex">
    </div>


    <x-filament::card>
        <div class="container mx-auto px-4">

            <form wire:submit="create">
                {{ $this->form }}
                <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                    <x-filament::button wire:click="submit" icon="fas-paper-plane" class="mt-5 px-5 py-4 bg-zinc-950 text-white font-semibold rounded-md shadow-md hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-opacity-75 text-base">
                        Enviar solicitação
                    </x-filament::button>
                </div>
            </form>


            <div style="margin-top: 60px">

                <section class="flex flex-col gap-y-8 py-8">
                    <header class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                                Agenda de entregas
                            </h1>
                        </div>
                    </header>

                    <div class="grid flex-1 auto-cols-fr gap-y-8">
                        @livewire(App\Filament\Bydconquista\Widgets\EntregasTable::class)
                    </div>
                </section>

            </div>
        </div>


        <style>
            :root {
                --primary-50: 242, 244, 246;
                --primary-100: 230, 233, 238;
                --primary-200: 191, 199, 211;
                --primary-300: 153, 165, 185;
                --primary-400: 77, 98, 133;
                --primary-500: 0, 30, 80;
                --primary-600: 0, 27, 72;
                --primary-700: 0, 23, 60;
                --primary-800: 0, 18, 48;
                --primary-900: 0, 15, 39;
                --primary-950: 0, 9, 24;
            }
        </style>
    </x-filament::card>
</div>

