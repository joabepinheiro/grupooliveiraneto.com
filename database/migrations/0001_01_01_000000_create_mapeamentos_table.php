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
        Schema::create('mapeamentos', function (Blueprint $table) {
            $table->id();

            $table->string('id_novo')->nullable();
            $table->string('id_antigo')->nullable();

            $table->string('table_origem')->nullable();
            $table->string('table_destino')->nullable();

            $table->string('bd_origem')->nullable();
            $table->longText('dados')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapeamentos');
    }
};
