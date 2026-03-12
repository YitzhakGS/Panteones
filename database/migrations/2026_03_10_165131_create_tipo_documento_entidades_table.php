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
        Schema::create('tipo_documento_entidades', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_tipo_documento');
        $table->foreign('id_tipo_documento')
            ->references('id_tipo_documento')
            ->on('tipo_documentos');
        $table->string('modelo', 255); // App\Models\Titular, App\Models\Beneficiario...
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_entidades');
    }
};
