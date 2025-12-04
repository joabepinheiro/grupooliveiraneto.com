<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AtualizarPermissoes extends Command
{

    protected $signature = 'atualizar-permissoes';
    protected $description = 'Move all files from disk public to disk local';

    public function handle()
    {

    }
}
