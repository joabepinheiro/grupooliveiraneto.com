<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class ResetDatabase extends Command
{
    protected $signature = 'rd';

    protected $description = 'Exclui todas as tabelas, executa as migrações e popula o banco de dados';

    public function handle()
    {
        if (!app()->environment(['local', 'development'])) {
            $this->error('⚠️ Este comando NÃO pode ser executado fora do ambiente de desenvolvimento.');
            return Command::FAILURE;
        }

        $this->info('Iniciando reset do banco de dados...');

        // Executa as migrações
        $this->info('Executando as migrações, recriando as tabelas...');
        $migrateOutput = '';
        Artisan::call('migrate:fresh', [], $migrateOutput);
        $this->line($migrateOutput);

        // Executa o seeding
        $this->info('Populando o banco de dados...');
        $seedOutput = '';
        Artisan::call('db:seed', ['--force' => true], $seedOutput);
        $this->line($seedOutput);

        $this->info('Banco de dados resetado e populado com sucesso!');

        return 0;
    }

    /**
     * Exibe a saída de um comando Artisan
     */
    private function executeArtisanCommand(string $command, array $parameters = []): void
    {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        Artisan::call($command, $parameters, $output);
        $this->line($output->fetch());
    }
}
