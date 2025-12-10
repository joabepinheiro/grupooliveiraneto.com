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

                $heading = Str::of($widget)->classBasename()->headline();

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

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Dados Gerais" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Faturamento" do formulário de entrega',
            'movelveiculos',
            ['Secretária de vendas']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Veículo na troca" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Nota fiscal" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Documentação / Veículo com placa" do formulário de entrega',
            'movelveiculos',
            ['Secretária de vendas']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Chave reserva" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Manuais: Proprietário, central multimídia e garantia/revisão (APP meu VW e/ou impresso)" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Fotos" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Revisão de entrega" do formulário de entrega',
            'movelveiculos',
            ['Oficina']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "O acessório já foi instalado?" do formulário de entrega',
            'movelveiculos',
            ['Secretária de vendas', 'Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Acessórios" do formulário de entrega',
            'movelveiculos',
            ['Secretária de vendas', 'Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Serviços estéticos" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Forma de pagamento" do formulário de entrega',
            'movelveiculos',
            ['Gerente financeiro']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Comprovantes de pagamento" do formulário de entrega',
            'movelveiculos',
            ['Gerente de vendas', 'Secretária de vendas']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Autorizado pelo financeiro" do formulário de entrega',
            'movelveiculos',
            ['Gerente financeiro']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar o campo "Assinatura do gerente financeiro" do formulário de entrega',
            'movelveiculos',
            ['Gerente financeiro']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Termo de entrega e aceite de veículo" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Observações e termos de aceite" do formulário de entrega',
            'movelveiculos',
            ['Entregador técnico']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Orientação CEM" do formulário de entrega',
            'movelveiculos',
            ['CRM']
        );

        Permission::createCustomPermissions(
            'Entrega - Alterar a seção "Orientação CSI" do formulário de entrega',
            'movelveiculos',
            ['CRM']
        );
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


