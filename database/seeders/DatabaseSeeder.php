<?php

namespace Database\Seeders;


use App\Models\User;
use Database\Seeders\MovelVeiculos\Entrega\AcessorioSeeder;
use Database\Seeders\MovelVeiculos\Entrega\EntregaSeeder;
use Database\Seeders\MovelVeiculos\Entrega\MediaSeeder;
use Database\Seeders\MovelVeiculos\Entrega\NotaSeeder;
use Database\Seeders\MovelVeiculos\Entrega\RevisaoSeeder;
use Database\Seeders\MovelVeiculos\Entrega\SegundoVeiculoNaTrocaSeeder;
use Database\Seeders\MovelVeiculos\Entrega\SolicitacaoDeEntregaSeeder;
use Database\Seeders\MovelVeiculos\Entrega\TermosDeEntregaSeeder;
use Database\Seeders\MovelVeiculos\RoleSeeder;
use Database\Seeders\Tarefa\OcorrenciaSeeder;
use Database\Seeders\Tarefa\TarefaSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desabilita verificações de chave estrangeira temporariamente.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->call([

                EmpresaSeeder::class,
                UserSeeder::class,
                ModeloSeeder::class,

                # Movel Veiculos
                RoleSeeder::class,
                SolicitacaoDeEntregaSeeder::class,
                EntregaSeeder::class,
                AcessorioSeeder::class,
                RevisaoSeeder::class,
                SegundoVeiculoNaTrocaSeeder::class,
                TermosDeEntregaSeeder::class,
                NotaSeeder::class,
                MediaSeeder::class,

                # BYDConquista
                \Database\Seeders\BYDConquista\RoleSeeder::class,
                \Database\Seeders\BYDConquista\SolicitacaoDeEntregaSeeder::class,
                \Database\Seeders\BYDConquista\EntregaSeeder::class,

                PermissionSeeder::class,

            ]);

            $user = User::find(1);
            $role = Role::find(1);
            $user->assignRole($role);
        } finally {
            // Reabilita verificações de chave estrangeira
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

}
