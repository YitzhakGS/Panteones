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
        Schema::create('documentos', function (Blueprint $table) {

            $table->id('id_documento');

            // Relación polimórfica
            $table->unsignedBigInteger('documentable_id')->nullable();
            $table->string('documentable_type', 255)->nullable();

            // Tipo de documento
            $table->unsignedBigInteger('id_tipo_documento');

            $table->foreign('id_tipo_documento')
                ->references('id_tipo_documento')
                ->on('tipo_documentos');

            // Archivo
            $table->string('archivo', 500);

            // Usuario que registró
            $table->unsignedBigInteger('registrado_por');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};