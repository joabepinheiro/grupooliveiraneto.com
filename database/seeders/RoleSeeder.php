<?php

namespace Database\Seeders;

use App\Enums\ProjetoStatus;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Projeto;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            #Papeis da movel
            'Usuário padrão',
            'Gerente de vendas',
            'Entregador técnico',
            'Vendedor',
            'Gerente financeiro',
            'Secretária de vendas',
            'Oficina',
            'CRM',
            'Visualisador',
            'Gerente de marketing',
            'Marketing',
            'Seminovos',
            'Analista de crédito',

            #Papeis da byd
            #'Gerente de vendas',
            #'Secretária de vendas',
            #'Entregador técnico',
            #'Gerente financeiro',
            #'Vendedor'

            'Administrador do sistema'
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::updateOrCreate(
                ['name' => $role],           // Condição de busca
                ['guard_name' => 'web']      // Valores para inserir/atualizar
            );
        }
    }

}
