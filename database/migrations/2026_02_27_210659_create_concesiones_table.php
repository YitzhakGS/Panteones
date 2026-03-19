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
        Schema::create('concesiones', function (Blueprint $table) {
            $table->id('id_concesion');

            $table->unsignedBigInteger('id_lote');
            $table->unsignedBigInteger('id_titular');
            $table->unsignedBigInteger('id_estatus');
            $table->unsignedBigInteger('id_uso_funerario');

            $table->enum('tipo', ['temporal', 'perpetuidad'])->default('temporal');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // null si es perpetuidad

            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_lote')->references('id_lote')->on('lotes');
            $table->foreign('id_titular')->references('id_titular')->on('titulares');
            $table->foreign('id_estatus')->references('id_estatus')->on('cat_estatus');
            $table->foreign('id_uso_funerario')->references('id_uso_funerario')->on('uso_funerario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concesiones');
    }
};
