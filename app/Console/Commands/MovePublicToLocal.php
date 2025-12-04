<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MovePublicToLocal extends Command
{

    protected $signature = 'files:move-public-to-local';
    protected $description = 'Move all files from disk public to disk local';

    public function handle()
    {
        $public = Storage::disk('public');
        $local = Storage::disk('local');

        // Pega todos os arquivos do public
        $files = $public->allFiles();

        foreach ($files as $file) {
            // Copia para o disk local mantendo a mesma estrutura
            $local->put($file, $public->get($file));

            // Remove do public
            $public->delete($file);
        }

        $this->info('Arquivos movidos com sucesso!');
        return Command::SUCCESS;
    }
}
