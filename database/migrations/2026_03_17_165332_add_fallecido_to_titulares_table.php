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
        Schema::table('titulares', function (Blueprint $table) {
            $table->boolean('fallecido')->default(false)->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('titulares', function (Blueprint $table) {
            $table->dropColumn('fallecido');
        });
    }
};
