<?php

namespace Database\Seeders;

use App\Enums\ProjetoStatus;
use App\Models\Cliente;
use App\Models\Projeto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class FilamentShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', '=', 'contato@joabepinheiro.com')->first();

        Artisan::call('shield:install', [
            'panel' => 'admin',
        ]);

        Artisan::call('shield:generate', [
            '--all'      => true,
            '--option'   => 'policies_and_permissions',
            '--panel' => 'admin',
        ]);

        Artisan::call('shield:super-admin', [
            '--panel' => 'admin',
            '--user' => $user->id,
        ]);
    }
}
