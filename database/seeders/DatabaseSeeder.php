<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desabilita verificações de chave estrangeira temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->call([
                EmpresaSeeder::class,
                RoleSeeder::class,          // Roles primeiro
                UserSeeder::class,          // Usuários depois
                FilamentShieldSeeder::class,
                SolicitacaoDeEntregaSeeder::class, // Se necessário
            ]);
        } finally {
            // Reabilita verificações de chave estrangeira
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

}
