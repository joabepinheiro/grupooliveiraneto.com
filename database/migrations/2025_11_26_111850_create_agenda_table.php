<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1.1. Tabela tarefas
         */
        Schema::create('agenda_tarefas', function (Blueprint $table) {
            $table->id();

            $table->string('agenda')->default('grupooliveiraneto');
            $table->string('titulo');
            $table->text('descricao')->nullable();

            $table->dateTime('data_inicio')->index(); // usado em listagens
            $table->dateTime('data_fim')->index();

            $table->string('rrule')->nullable()->index(); // usado em buscas de tarefas recorrentes

            $table->string('status')->default('pendente')->index(); // filtros por status

            $table->text('responsaveis')->nullable();
            $table->string('departamentos', 255)->nullable();

            $table->boolean('recorrencia_tem')->nullable();
            $table->integer('recorrencia_intervalo')->nullable();
            $table->string('recorrencia_frequencia')->nullable();
            $table->string('recorrencia_dias_semana', 255)->nullable();
            $table->string('recorrencia_tipo_fim')->nullable();
            $table->dateTime('recorrencia_data_fim')->nullable();
            $table->integer('recorrencia_quantidade_ocorrencias')->nullable();


            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();

            $table->timestamps();
            $table->softDeletes()->index(); // MUITO importante para performance

            // FKs
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
        });



        Schema::create('agenda_ocorrencias_tarefas', function (Blueprint $table) {
            $table->id();

            $table->string('agenda')->default('grupooliveiraneto');
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();

            $table->dateTime('data_inicio')->index(); // usado em listagens
            $table->dateTime('data_fim')->index();


            $table->foreignId('tarefa_id')
                ->constrained('agenda_tarefas')
                ->cascadeOnDelete();

            $table->string('status')->default('pendente')->index();

            $table->text('responsaveis')->nullable();
            $table->string('departamentos', 255)->nullable();

            $table->boolean('is_excecao')->default(false)->index();
            // geralmente usado em filtros, então index ajuda muito

            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedBigInteger('deleted_by')->nullable()->index();

            $table->timestamps();
            $table->softDeletes()->index();

            // FKs
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();

            // Índice composto que acelera consultas do tipo:
            // ->where('tarefa_id', X)->whereDate('data_inicio', Y)
            $table->index(['tarefa_id', 'data_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_ocorrencias_tarefas');
        Schema::dropIfExists('agenda_tarefa_user');
        Schema::dropIfExists('agenda_tarefas');
    }
};
