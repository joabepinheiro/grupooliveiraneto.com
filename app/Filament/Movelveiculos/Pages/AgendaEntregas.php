<?php

namespace App\Filament\Movelveiculos\Pages;

use App\Filament\Movelveiculos\Resources\Entregas\EntregaResource;
use App\Filament\Movelveiculos\Widgets\EntregasTable;
use BackedEnum;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class AgendaEntregas extends Page
{
    use HasFiltersForm;

    protected static string | BackedEnum | null $navigationIcon = 'fas-calendar-days';

    protected static string $resource = EntregaResource::class;

    protected static ?string $title = 'Agenda de entregas';

    protected static ?string $navigationLabel = 'Agenda de entregas';

    protected static ?int $navigationSort = 12;


    protected function getHeaderWidgets(): array
    {
        return [
            EntregasTable::class
        ];
    }


}
