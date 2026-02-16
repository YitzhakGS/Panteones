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
        Schema::create('cat_cuadrillas', function (Blueprint $table) {
            $table->id('id_cuadrilla');

            $table->unsignedBigInteger('id_seccion');

            $table->foreign('id_seccion')
                ->references('id_seccion')
                ->on('cat_secciones');
            
            $table->string('nombre');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_cuadrillas');
    }
};
