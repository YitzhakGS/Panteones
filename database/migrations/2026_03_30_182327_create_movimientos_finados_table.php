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
            $table->unsignedBigInteger('id_concesion')->nullable(); //destino del movimiento
            $table->unsignedBigInteger('id_concesion_origen')->nullable(); //origen del movimiento (solo para exhumaciones)

            $table->enum('tipo', ['inhumacion', 'exhumacion', 'reinhumacion']);
             // inhumacion, exhumacion, reinhumacion
            $table->date('fecha');

            $table->text('observaciones')->nullable();

            $table->string('ubicacion_destino_externa')->nullable(); 
            $table->boolean('es_misma_ubicacion')->default(false);
            $table->string('solicitante')->nullable();


            $table->timestamps();
            $table->softDeletes();

            // 🔗 FKs
            $table->foreign('id_finado')
                  ->references('id_finado')
                  ->on('finados');

            $table->foreign('id_concesion')
                  ->references('id_concesion')
                  ->on('concesiones');

            $table->foreign('id_concesion_origen')
                  ->references('id_concesion')
                  ->on('concesiones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_finados');
    }
};
