<?php

namespace Database\Seeders;

use Database\Seeders\Tarefa\EventoAlteracaoSeeder;
use Database\Seeders\Tarefa\EventoMembroSeeder;
use Database\Seeders\Tarefa\EventoSeeder;
use Database\Seeders\Tarefa\LembreteSeeder;
use Database\Seeders\Tarefa\RegraRecorrenciaSeeder;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EventoSeeder::class,
            RegraRecorrenciaSeeder::class,
            EventoMembroSeeder::class,
            EventoAlteracaoSeeder::class,
            LembreteSeeder::class,
        ]);
    }
}
