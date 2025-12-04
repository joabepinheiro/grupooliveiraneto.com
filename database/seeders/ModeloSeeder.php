<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use App\Models\Modelo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ModeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelos = [
            [
                "id" => 1,
                "nome" => "Polo Track",
                "cores" => ["Cinza Platinum", "Prata Sirius", "Vermelho Sunset", "Branco Cristal", "Preto Ninja"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 2,
                "nome" => "Polo",
                "cores" => ["Cinza Platinum", "Prata Sirius", "Vermelho Sunset", "Branco Cristal", "Preto Ninja"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 3,
                "nome" => "Virtus",
                "cores" => ["Surfing Blue & Dolphin Grey", "Azul Biscay", "Cinza Platinum", "Prata Sirius", "Branco Cristal", "Preto Ninja"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 4,
                "nome" => "Jetta",
                "cores" => ["Azul Rising", "Vermelho Kings", "Preto Mystic", "Branco Puro", "Cinza Puro", "Atlantis Grey"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 5,
                "nome" => "Nivus",
                "cores" => ["Skiing White", "Surfing Blue", "Climbing Grey", "Adventuring Green", "Branco Cristal", "Preto Ninja", "Prata Sirius", "Cinza Platinum", "Vermelho Sunset", "Cinza Moonstone", "Azul Titan", "Azul turbo"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 6,
                "nome" => "T-Cross",
                "cores" => ["Prata Pyrit", "Branco Puro", "Preto Ninja", "Milky Gray-green", "Cinza Platinum", "Cinza Ascot"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 7,
                "nome" => "Taos",
                "cores" => ["Cinza Indium", "Prata Pyrit", "Preto Mystic", "Branco Puro", "Mojave Beje"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 8,
                "nome" => "ID.4",
                "cores" => ["Dome Blue", "Snow White", "Time Grey", "Delan Black", "Moonstone Gray", "Blue Dusk"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 9,
                "nome" => "Nova Saveiro",
                "cores" => ["Prata Sirius", "Branco Cristal", "Preto Ninja", "Cinza Oliva"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 10,
                "nome" => "Nova Amarok",
                "cores" => ["Azul Atlantic", "Cinza Indium", "Cinza Oliver", "Prata Pyrit", "Preto Mystic", "Branco Puro"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 11,
                "nome" => "BYD TAN",
                "cores" => ["Snow White", "Silver Sand Black", "Mountain Grey"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
                "deleted_at" => '2024-11-25 10:41:21',
            ],
            [
                "id" => 12,
                "nome" => "BYD Shark",
                "cores" => ["Branco", "Preto", "Azul"],
                "created_by" => 6,
                "updated_by" => null,
                "deleted_by" => null,
                "deleted_at" => '2024-11-25 10:41:21',
            ],
            [
                "id" => 13,
                "nome" => "ID. Buzz",
                "cores" => [],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
                "deleted_at" => '2024-11-25 10:43:16',
            ],
            [
                "id" => 14,
                "nome" => "Tera",
                "cores" => ["Preto Ninja", "Branco Puro", "Cinza Platinum", "Vermelho Sunset", "Prata Pyrit", "Azul Norway", "Cinza Ascot"],
                "created_by" => 9,
                "updated_by" => null,
                "deleted_by" => null,
            ],
        ];

        foreach ($modelos as $modelo) {
            $novo = Modelo::create(
                [
                    'nome'       => $modelo['nome'],
                    'empresa_id' => Empresa::MOVEL_VEICULOS_ID,
                    'cores'      => $modelo['cores'] ?? [],
                    'created_by' =>  Mapeamento::getNovoId('users', $modelo['created_by'], 'movelveiculos'),
                    'updated_by' =>  Mapeamento::getNovoId('users', $modelo['updated_by'], 'movelveiculos'),
                    'deleted_at' =>  $modelo['deleted_at'] ?? null,
                ],
            );

            Mapeamento::create([
                'id_novo'       => $novo->id,
                'id_antigo'     => $modelo['id'],
                'table_origem'  => 'modelos',
                'table_destino' => 'modelos'  ,
                'bd_origem'     => 'movelveiculos',
                'dados'         => $modelo,
            ]);
        }


        $modelos = [
            [
                "id" => 1,
                "nome" => "BYD DOLPHIN MINI",
                "cores" => ["Apricity White", "Peach Pink", "Polar Night Black", "Sprout Green"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 2,
                "nome" => "BYD DOLPHIN",
                "cores" => ["Dolphin Grey", "Afterglow Pink", "Sand White", "Delan Black"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 3,
                "nome" => "BYD DOLPHIN PLUS",
                "cores" => [
                    "Surfing Blue & Dolphin Grey",
                    "Surfing Blue & Dolphin Grey",
                    "Delan Black & Atlantis Grey",
                    "Afterglow Pink & Dolphin Grey",
                    "Ski White & Dolphin Grey",
                ],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 4,
                "nome" => "BYD SEAL",
                "cores" => ["Rosemary Grey", "Glacier Blue", "Cosmos Black", "Bright White", "Courtyard Green", "Atlantis Grey"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 5,
                "nome" => "BYD YUAN PLUS",
                "cores" => ["Skiing White", "Surfing Blue", "Climbing Grey", "Adventuring Green"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 6,
                "nome" => "BYD YUAN PRO",
                "cores" => ["Malachite Darkcyan", "Time Grey", "Skiing White", "Milky Gray-green"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 7,
                "nome" => "BYD SONG PRO",
                "cores" => ["Azul Atlântida", "Snow White", "Time Grey"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 8,
                "nome" => "BYD SONG PLUS",
                "cores" => ["Dome Blue", "Snow White", "Time Grey", "Delan Black"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 9,
                "nome" => "BYD KING",
                "cores" => ["Cosmos Black", "Time Grey", "Snow White"],
                "created_by" => 1,
                "updated_by" => 1,
                "deleted_by" => null,
            ],
            [
                "id" => 10,
                "nome" => "BYD HAN",
                "cores" => ["Emperor Red", "Time Grey", "Space Black", "Skiing White"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 11,
                "nome" => "BYD TAN",
                "cores" => ["Snow White", "Silver Sand Black", "Mountain Grey"],
                "created_by" => 1,
                "updated_by" => null,
                "deleted_by" => null,
            ],
            [
                "id" => 12,
                "nome" => "BYD Shark",
                "cores" => ["Branco", "Preto", "Azul"],
                "created_by" => 6,
                "updated_by" => null,
                "deleted_by" => null,
            ],
        ];


        foreach ($modelos as $modelo) {
            $novo = Modelo::create(
                [
                    'nome'       => $modelo['nome'],
                    'empresa_id'  => Empresa::BYD_CONQUISTA_ID,
                    'cores'      => $modelo['cores'] ?? [],
                    'created_by' =>  Mapeamento::getNovoId('users', $modelo['created_by'], 'bydconquista'),
                    'updated_by' =>  Mapeamento::getNovoId('users', $modelo['updated_by'], 'bydconquista'),
                ],
            );

            Mapeamento::create([
                'id_novo'       => $novo->id,
                'id_antigo'     => $modelo['id'],
                'table_origem'  => 'modelos',
                'table_destino' => 'modelos'  ,
                'bd_origem'     => 'bydconquista',
                'dados'         => $modelo,
            ]);
        }
    }
}
