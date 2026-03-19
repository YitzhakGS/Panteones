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
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id('id_beneficiario');

            $table->integer('orden')->default(1);
            $table->unsignedBigInteger('id_titular');

            $table->foreign('id_titular')
                ->references('id_titular')
                ->on('titulares');

            $table->string('nombre');
            $table->string('domicilio');
            $table->string('colonia');
            $table->string('codigo_postal', 10);
            $table->string('municipio');
            $table->string('estado');
            $table->string('telefono', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiarios');
    }
};
