<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitacoes_de_entrega', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_id')->nullable();

            $table->unsignedBigInteger('entrega_id')->nullable();
            $table->string('status')->nullable()->default('Solicitada');

            $table->string('tipo_venda')->nullable();
            $table->dateTime('data_prevista')->nullable();
            $table->dateTime('entrega_efetivada_em')->nullable();
            $table->string('proposta')->nullable();
            $table->string('cliente')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->string('modelo')->nullable();
            $table->string('cor')->nullable();
            $table->string('chassi')->nullable();

            $table->boolean('foi_solicitado_emplacamento')
                ->default(0)
                ->nullable();

            $table->boolean('foi_solicitado_acessorio')
                ->default(0)
                ->nullable();

            $table->json('acessorios_solicitados')
                ->nullable(); // Laravel casta como array

            $table->string('brinde')
                ->nullable();


            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('vendedor_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_de_entrega');
    }
};
