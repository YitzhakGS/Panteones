<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimientos_finados', function (Blueprint $table) {
            $table->id('id_movimiento');

            $table->unsignedBigInteger('id_finado');
            $table->unsignedBigInteger('id_ubicacion_actual')->nullable();

            $table->string('ubicacion_actual')->nullable();
            $table->string('ubicacion_anterior')->nullable();

            $table->enum('tipo', ['inhumacion', 'exhumacion', 'reinhumacion', 'movimiento']);
            $table->date('fecha');
            $table->string('solicitante')->nullable();
            $table->text('observaciones')->nullable();

            $table->boolean('es_misma_ubicacion')->default(false);
            $table->boolean('es_externo')->default(false);
            $table->string('ubicacion_externa')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_finado')
                  ->references('id_finado')
                  ->on('finados');

            $table->foreign('id_ubicacion_actual')
                  ->references('id_concesion')
                  ->on('concesiones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_finados');
    }
};