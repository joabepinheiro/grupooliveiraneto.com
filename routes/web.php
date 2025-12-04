<?php
use Symfony\Component\Finder\Finder;
use App\Models\Entrega\Entrega;
use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect()->to('/admin');
});


Route::get('/teste', function () {

    $ocorrencia = \App\Models\Tarefa\Ocorrencia::query()->first();
    dd($ocorrencia->createdBy);


});

function getAllModels(): array
{
    $models = [];
    $modelPath = app_path('Models');

    // 1. Verifica se o diretório de Models existe
    if (! is_dir($modelPath)) {
        // Se a pasta 'app/Models' não existir, tenta 'app/' (configuração antiga do Laravel)
        $modelPath = app_path();
    }

    // 2. Cria um Finder para localizar arquivos PHP
    $finder = (new Finder())->files()->in($modelPath)->name('*.php');

    // 3. Itera sobre os arquivos encontrados
    foreach ($finder as $file) {
        $namespace = 'App\\Models\\';

        // Determina o nome completo da classe
        $relativePath = $file->getRelativePathname(); // Ex: User.php

        // Remove .php e converte para nome de classe com namespace (App\Models\User)
        $className = str_replace(
            ['/', '.php'],
            ['\\', ''],
            $namespace . $relativePath
        );

        // 4. Verifica se a classe existe e se herda de Model
        if (class_exists($className) && is_subclass_of($className, \Illuminate\Database\Eloquent\Model::class)) {
            $models[] = $className;
        }
    }

    return $models;
}



Route::get('/private-image/{path}', function ($path) {

    $disk = Storage::disk('local');
    if (!$disk->exists($path)) {
        abort(404, 'Arquivo não encontrado');
    }
    $file = $disk->get($path);
    $mimeType = $disk->mimeType($path);
    return Response::make($file, 200)->header("Content-Type", $mimeType);
})->where('path', '.*')
    ->middleware('auth')
    ->name('private.image');


Route::get('/private-image/download/{path}', function ($path) {
    $disk = Storage::disk('local');

    if (!$disk->exists($path)) {
        abort(404, 'Arquivo não encontrado');
    }

    $filename = basename($path); // Nome sugerido para download

    return $disk->download($path, $filename);
})->where('path', '.*')->middleware('auth')->name('private.image.download');

