<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            ['id' => 1, 'nome' => 'Grupo Oliveira Neto', 'nome_curto' => 'GON'],
            ['id' => 2, 'nome' => 'Movel Veículos', 'nome_curto' => 'MV'],
            ['id' => 3, 'nome' => 'BYD Conquista', 'nome_curto' => 'BYD'],
            ['id' => 4, 'nome' => 'Movel Caminhões', 'nome_curto' => 'MC'],
        ];

        foreach ($empresas as $empresa) {
            Empresa::updateOrCreate(
                ['id' => $empresa['id']],
                $empresa
            );
        }

    }
}
