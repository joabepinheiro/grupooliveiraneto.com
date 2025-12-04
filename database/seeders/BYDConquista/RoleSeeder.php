<?php

namespace Database\Seeders\BYDConquista;

use App\Models\Empresa;
use App\Models\Entrega\Entrega;
use App\Models\Mapeamento;
use App\Models\Role;
use App\Providers\Filament\MovelveiculosPanelProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{


    private string $bd_origem = 'bydconquista';
    private int $empresa_id =  Empresa::BYD_CONQUISTA_ID;


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar foreign key checks temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $roles = DB::connection('mysql_bydconquista')
                ->table('roles')
                ->get();

            foreach ($roles as $role) {

                $novo = Role::updateOrCreate([
                    'id' => null,
                ], [
                    'panel_id' => 'bydconquista',

                    'name' => $role->name,
                    'guard_name' => $role->guard_name,

                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                ]);

                Mapeamento::create([
                    'id_novo'       => $novo->id,
                    'id_antigo'     => $role->id,
                    'table_origem'  => 'entregas',
                    'table_destino' => 'entregas',
                    'bd_origem'     => $this->bd_origem,
                    'dados'         => $role,
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
