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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id('id_lote');

            $table->unsignedBigInteger('id_espacio_fisico');
            $table->unsignedBigInteger('id_estado_lote');

            $table->foreign('id_espacio_fisico')
                ->references('id_espacio_fisico')
                ->on('espacios_fisicos');

            $table->foreign('id_estado_lote')
                ->references('id_estado_lote')
                ->on('cat_estado_lote');

            $table->string('numero');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
