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

            $table->date('fecha_refrendo');
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_fin')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            

            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])
             ->default('pendiente');

            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes(); 

            // Foreign key
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
