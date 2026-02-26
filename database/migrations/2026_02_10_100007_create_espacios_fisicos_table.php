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
        Schema::create('espacios_fisicos', function (Blueprint $table) {
            $table->id('id_espacio_fisico');

            $table->unsignedBigInteger('id_cuadrilla');
            $table->unsignedBigInteger('id_tipo_espacio_fisico');
            
            $table->foreign('id_cuadrilla')
                ->references('id_cuadrilla')
                ->on('cat_cuadrillas');

            $table->foreign('id_tipo_espacio_fisico')
                ->references('id_tipo_espacio_fisico')
                ->on('cat_tipos_espacio_fisico');             

            $table->string('nombre');
            $table->string('descripcion')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios_fisicos');
    }
};
