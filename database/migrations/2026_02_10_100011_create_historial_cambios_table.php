<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_cambios', function (Blueprint $table) {
            $table->id('id_historial');

            // Qué entidad cambió
            $table->string('entidad'); // Titular, Lote, EspacioFisico

            // PK del registro afectado
            $table->unsignedBigInteger('id_registro');

            // Campo que cambió
            $table->string('campo'); // telefono, nombre, domicilio, etc.

            // Acción
            $table->foreignId('id_accion')
                ->constrained('cat_acciones_auditoria', 'id_accion');

            // Valores
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();

            // Usuario
            $table->foreignId('id_usuario')
                ->nullable()
                ->constrained('users');

            // Motivo
            $table->string('motivo')->nullable();

            // Fecha del cambio
            $table->timestamp('created_at')->useCurrent();

            // Índice útil
            $table->index(['entidad', 'id_registro']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_cambios');
    }
};