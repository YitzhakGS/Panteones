<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config_global', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('valor')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
 
        // Insertar la clave con valor null por defecto
        DB::table('config_global')->insert([
            'clave'       => 'fecha_limite_pago',
            'valor'       => null,
            'descripcion' => 'Fecha límite de pago municipal para todos los refrendos pendientes',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
 
    public function down(): void
    {
        Schema::dropIfExists('config_global');
    }
};
