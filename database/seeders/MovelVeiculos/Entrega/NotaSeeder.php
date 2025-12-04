<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\Nota;
use App\Models\Entrega\SolicitacaoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotaSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id =  Empresa::MOVEL_VEICULOS_ID;

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $notas = DB::connection('mysql_movelveiculos')
                ->table('notas')
                ->get();

            foreach ($notas as $nota) {

                Nota::updateOrCreate([
                    'id' => $nota->id,
                ], [
                    'codigo' => $nota->codigo,
                    'nota' => $nota->nota,
                    'grupo' => $nota->grupo,
                    'atributos' => $nota->atributos,
                    'morphable_id' => $nota->morphable_id,
                    'morphable_type' => $nota->morphable_type,

                    'created_by'    => Mapeamento::getNovoId('users', $nota->created_by, $this->bd_origem),
                    'updated_by'    => Mapeamento::getNovoId('users', $nota->updated_by, $this->bd_origem),
                    'deleted_by'    => Mapeamento::getNovoId('users', $nota->deleted_by, $this->bd_origem),

                    'created_at' => $nota->created_at,
                    'updated_at' => $nota->updated_at,
                    'deleted_at' => $nota->deleted_at,
                ]);
            }
        }
        catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        }
        finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
