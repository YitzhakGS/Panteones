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
        Schema::create('espacio_fisico_lote', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_espacio_fisico')
                ->constrained('espacios_fisicos', 'id_espacio_fisico');

            $table->foreignId('id_lote')
                ->constrained('lotes', 'id_lote');

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacio_fisico_lote');
    }
};
