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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id('id_lote');
            $table->string('numero');
            $table->decimal('metros_cuadrados', 8, 2)->nullable();

            $table->string('col_norte')->nullable();
            $table->string('col_sur')->nullable();
            $table->string('col_oriente')->nullable();
            $table->string('col_poniente')->nullable();

            $table->decimal('med_norte', 8, 2)->nullable();
            $table->decimal('med_sur', 8, 2)->nullable();
            $table->decimal('med_oriente', 8, 2)->nullable();
            $table->decimal('med_poniente', 8, 2)->nullable();

            $table->text('referencias')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
