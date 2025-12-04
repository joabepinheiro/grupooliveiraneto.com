<?php

namespace Database\Seeders\MovelVeiculos;

use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use App\Models\Mapeamento;
use App\Models\Role;
use App\Providers\Filament\MovelveiculosPanelProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id =  Empresa::MOVEL_VEICULOS_ID;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar foreign key checks temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $roles = DB::connection('mysql_movelveiculos')
                ->table('roles')
                ->get();

            foreach ($roles as $role) {

                Role::updateOrCreate([
                    'id' => $role->id,
                ], [
                    'panel_id' => 'movelveiculos',

                    'name' => $role->name,
                    'guard_name' => $role->guard_name,

                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ]);

            }
        } catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        } finally {
            // Reabilitar foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

}
