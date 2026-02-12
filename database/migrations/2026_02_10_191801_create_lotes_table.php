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

            $table->foreignId('id_espacio_fisico')
                ->constrained('espacios_fisicos');

            $table->foreignId('id_tipo_espacio')
                ->constrained('cat_tipos_espacio');

            $table->foreign('id_estado_lote')
                ->constrained('cat_estado_lote');

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
