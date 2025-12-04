<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\Permission;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }



    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = $this->record->permissions;

        $data['model'] = $permissions
            ->where('tipo', '=', 'model')
            ->groupBy('className')
            ->mapWithKeys(function ($items, $resource) {
                $key = str_replace('\\', '_', $resource);
                return [$key => $items->pluck('id')->all()];
            })
            ->toArray();

        $data['custom'] = $permissions
            ->where('tipo', '=', 'custom')
            ->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {


        $modelPermissions = collect($data['model'] ?? [])
            ->flatten()
            ->filter()
            ->unique()
            ->all();


        $customPermissions = collect($data['custom'] ?? [])
            ->filter()             // Mantém somente itens marcados (true)
            ->keys()               // Usa o índice (ID da permissão)
            ->all();

        // Junta tudo em um único array de IDs de permissão
        $allPermissions = array_values(array_unique([
            ...$modelPermissions,
            ...$customPermissions,
        ]));

        // Atualiza pivot
        $this->record->permissions()->sync($allPermissions);

        return $data;
    }


}
