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
        Schema::create('rol', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('rol')->nullable(); // Columna para la clave foránea
            $table->foreign('rol')->references('id')->on('rol')->onDelete('set null'); // Definición de la clave foránea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol']); // Elimina la clave foránea
            $table->dropColumn('rol');   // Elimina la columna
        });

        Schema::dropIfExists('rol');
    }
};
