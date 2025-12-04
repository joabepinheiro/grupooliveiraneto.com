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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('panel_id')->nullable()->after('id');
            $table->string('titulo')->nullable()->after('panel_id');
            $table->string('descricao')->nullable()->after('panel_id');
            $table->string('tipo')->nullable()->after('descricao');

            $table->string('className')->nullable()->after('descricao');
            $table->string('action')->nullable()->after('descricao');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('panel_id');

            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');

            $table->dropColumn('deleted_at');
        });
    }
};
