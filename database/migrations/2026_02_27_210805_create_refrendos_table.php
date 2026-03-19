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
        Schema::create('refrendos', function (Blueprint $table) {
            $table->id('id_refrendo');

            $table->unsignedBigInteger('id_concesion');

            $table->enum('tipo_refrendo', ['mantenimiento', 'administrativo'])
                ->default('mantenimiento');

            $table->date('fecha_refrendo');      // Fecha en que se registra el refrendo
            $table->date('fecha_inicio');         // Inicio del periodo que cubre
            $table->date('fecha_fin');            // Fin del periodo que cubre
            $table->date('fecha_limite_pago');    // Fecha límite para pagar (puede diferir de fecha_fin)

            $table->decimal('monto', 10, 2)->nullable();

            $table->enum('estado', ['pendiente', 'pagado', 'vencido', 'cancelado'])
                ->default('pendiente');

            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_concesion')
                ->references('id_concesion')
                ->on('concesiones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refrendos');
    }
};
