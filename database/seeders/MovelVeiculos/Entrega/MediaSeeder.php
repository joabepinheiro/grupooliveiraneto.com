<?php

namespace Database\Seeders\MovelVeiculos\Entrega;

use App\Models\Empresa;
use App\Models\Entrega\TermoDeEntrega;
use App\Models\Mapeamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaSeeder extends Seeder
{

    private string $bd_origem = 'movelveiculos';
    private int $empresa_id = Empresa::MOVEL_VEICULOS_ID;

    /**
     * @throws \Exception
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $medias = DB::connection('mysql_movelveiculos')
                ->table('media')
                ->get();

            foreach ($medias as $media) {

                if($media->model_type == 'App\Models\Entrega'){
                    $model_type = 'App\Models\Entrega\Entrega';
                }else{
                    $model_type = $media->model_type;
                }

                Media::updateOrCreate([
                    'id' => $media->id,
                ], [
                    'model_type' => $model_type,
                    'model_id' => $media->model_id,
                    'uuid' => $media->uuid,
                    'collection_name' => $media->collection_name,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'disk' => 'local',
                    'conversions_disk' => 'local',
                    'size' => $media->size,
                    'manipulations' => json_decode($media->manipulations,true),
                    'custom_properties' => json_decode($media->custom_properties, true),
                    'generated_conversions' => json_decode($media->generated_conversions, true),
                    'responsive_images' => json_decode($media->responsive_images, true),
                    'order_column' => $media->order_column,
                    'created_at' => $media->created_at,
                    'updated_at' => $media->updated_at,
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error("Erro durante a importação: " . $e->getMessage());
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
