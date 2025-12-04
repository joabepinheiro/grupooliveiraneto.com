<?php

namespace Database\Seeders;

use App\Models\AbstractModel;
use App\Models\Empresa;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use App\Models\Modelo;
use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'ViewAny',
            'View',
            'Create',
            'Update',
            'Delete',
            'Restore',
            'ForceDelete',
            'ForceDeleteAny',
            'RestoreAny',
            'Replicate',
            'Reorder',
            'DeleteAny',
        ];

        $models = $this->getAllModels();

        foreach (Filament::getPanels() as $panel) {
            foreach ($models as $model) {
                foreach ($actions as $action){
                    Permission::updateOrCreate(
                    [
                       'name' => $panel->getId(). '::'.$model.'.'.$action
                    ],
                    [
                       'name'       => $panel->getId(). '::'.$model.'.'.$action,
                       'panel_id'   => $panel->getId(),
                       'className'  => $model,
                       'action'     => $action,
                       'titulo'      => self::translation($action),
                       'descricao'  => $model::getModelLabel() . ' - ' . self::translation($action),
                       'tipo'       => 'model',
                       'guard_name'  => 'web',
                    ]);
                }
            }
        }

        foreach (Filament::getPanels() as $panel) {
            foreach ($panel->getPages() as $page) {
                Permission::updateOrCreate(
                    [
                        'name' =>  $panel->getId(). '::'.$page,
                    ],
                    [
                    'name'     => $panel->getId(). '::'.$page,
                    'panel_id' => $panel->getId(),
                    'className' => $page,
                    'descricao'  => ucfirst($page::getNavigationLabel()),
                    'tipo'     => 'page',
                    'guard_name'  => 'web',
                ]);
            }
        }

        foreach (Filament::getPanels() as $panel) {

            foreach ($panel->getWidgets() as $widget) {

                // Garantir que é uma classe válida
                if (! class_exists($widget)) {
                    continue;
                }

                // Obter heading / título do widget
                $heading = null;

                if (method_exists($widget, 'getHeading')) {
                    $heading = $widget::getHeading();
                } elseif (method_exists($widget, 'getTitle')) {
                    $heading = $widget::getTitle();
                } else {
                    // Fallback: nome da classe sem namespace
                    $heading = Str::of($widget)->classBasename()->headline();
                }

                Permission::updateOrCreate(
                    [
                        'name' => $panel->getId() . '::' . $widget,
                    ],
                    [
                    'name'        => $panel->getId() . '::' . $widget,
                    'panel_id'    => $panel->getId(),
                    'className'      => $widget,
                    'descricao'   => $heading,
                    'tipo'        => 'widget',
                    'guard_name'  => 'web',
                ]);
            }
        }

    }

    public static function translation($index)
    {
        $array = [
            'ViewAny'           => 'Ver todos',
            'View'              => 'Visualizar',
            'Create'            => 'Cadastrar',
            'Update'            => 'Editar',
            'Delete'            => 'Excluir',
            'Restore'           => 'Restaurar',
            'ForceDelete'       => 'Excluir permanentemente',
            'ForceDeleteAny'    => 'Excluir permanentemente (vários)',
            'RestoreAny'        => 'Restaurar (vários)',
            'Replicate'         => 'Duplicar',
            'Reorder'           => 'Reordenar',
            'DeleteAny'         => 'Excluir (vários)',
            'bydconquista'      => 'BYD Conquista',
            'movelveiculos'     => 'Movel Veículos',
            'admin'             => 'Administração',
            'grupooliveiraneto'   => 'Grupo Oliveira Neto',
        ];

        return $array[$index] ?? $index;
    }

    function getAllModels(): array
    {

        return [
            'App\Models\Role',
            'App\Models\Permission',
            'App\Models\ActivityLog',
            'App\Models\User',
            //'App\Models\Media',
            'App\Models\Mapeamento',
            'App\Models\Empresa',
            'App\Models\Modelo',
            'App\Models\Entrega\EntregaHorarioBloqueado',
            'App\Models\Entrega\SolicitacaoDeEntrega',
            'App\Models\Entrega\Entrega',
        ];
    }

}


