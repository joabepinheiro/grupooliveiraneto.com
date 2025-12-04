<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Mapeamento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando seed de usuários...');

        try {

            // Desabilitar foreign key checks temporariamente
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Criação do super admin inicial
            $this->createSuperAdmin();

            $this->importarDadosDaMovelVeiculos();
            $this->importarDadosDaBydConquista();

            // Reabilitar foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info('Seed de usuários concluído com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro no UserSeeder: " . $e->getMessage());
            $this->command->error("Erro ao processar seed: " . $e->getMessage());
        }
    }

    /**
     * Cria o super admin inicial.
     */
    private function createSuperAdmin(): void
    {
        $this->command->info('Criando super admin...');

        $user = User::updateOrCreate(
            ['email' => 'contato@joabepinheiro.com'],
            [
                'name'     => 'Joabe Pinheiro',
                'first_name'     => 'Joabe',
                'last_name'     => 'Pinheiro',
                'username'    => 'joabepinheiro',
                'password' => Hash::make('maquened'),
                'status'   => 'Ativo',
            ]
        );

    }

    public function importarDadosDaMovelVeiculos(): void
    {

        try {
            $usersMovel = DB::connection('mysql_movelveiculos')
                ->table('users')
                ->get();

            foreach ($usersMovel as $antigo) {
                // Criando no novo sistema
                $novo = User::updateOrCreate([
                    'email' => $antigo->email,
                ],
                [
                    'first_name'        => $antigo->first_name,
                    'last_name'         => $antigo->last_name,
                    'name'              => $antigo->name,
                    'username'             => $antigo->username ?? Str::slug($antigo->first_name . ' '.$antigo->last_name),
                    'email'             => $antigo->email,
                    'email_verified_at' => $antigo->email_verified_at,
                    'password'          => $antigo->password,
                    'status'            => $antigo->is_active ? 'Ativo' : 'Desativado',
                    'remember_token'    => $antigo->remember_token,

                    'created_at'        => $antigo->created_at,
                    'updated_at'        => $antigo->updated_at,
                    'deleted_at'        => $antigo->deleted_at,
                ]);

                $novo->empresas()->attach(Empresa::MOVEL_VEICULOS_ID);

                Mapeamento::create([
                    'id_novo'       => $novo->id,
                    'id_antigo'     => $antigo->id,
                    'table_origem'  => 'users',
                    'table_destino' => 'users'  ,
                    'bd_origem'     => 'movelveiculos',
                    'dados'         => $antigo,
                ]);
            }
        }
        catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        }
    }

    public function importarDadosDaBydConquista(): void
    {
        try {
            $usersByd = DB::connection('mysql_bydconquista')
                ->table('users')
                ->get();

            foreach ($usersByd as $antigo) {
                // Criando no novo sistema
                $novo = User::updateOrCreate([
                    'email' => $antigo->email,
                ],
                    [
                        'first_name'    => $antigo->first_name ?? NULL,
                        'last_name'     => $antigo->last_name ?? NULL,
                        'name'          => $antigo->name,
                        'username'         => $antigo->username ?? Str::slug($antigo->name),
                        'email'         => $antigo->email,
                        'email_verified_at' => $antigo->email_verified_at,
                        'password'          => $antigo->password,
                        'status'            => 'Ativo',
                        'remember_token'    => $antigo->remember_token,

                        'created_at'        => $antigo->created_at,
                        'updated_at'        => $antigo->updated_at,
                        'deleted_at'        => $antigo->deleted_at,
                    ]);

                $novo->empresas()->attach(Empresa::BYD_CONQUISTA_ID);

                Mapeamento::create([
                    'id_novo'       => $novo->id,
                    'id_antigo'     => $antigo->id,
                    'table_origem'  => 'users',
                    'table_destino' => 'users'  ,
                    'bd_origem'     => 'bydconquista',
                    'dados'         => $antigo,
                ]);
            }
        }
        catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        }
    }
}
