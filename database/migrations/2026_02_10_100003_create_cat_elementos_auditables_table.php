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
        Schema::create('cat_elementos_auditables', function (Blueprint $table) {
            $table->id('id_elemento');
            $table->string('entidad');
            $table->string('campo');
            $table->timestamps();

            $table->unique(['entidad', 'campo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_elementos_auditables');
    }
};
