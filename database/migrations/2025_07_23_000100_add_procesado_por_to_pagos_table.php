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
        Schema::table('pagos', function (Blueprint $table) {
            // Agregar la columna procesado_por si no existe
            if (!Schema::hasColumn('pagos', 'procesado_por')) {
                $table->unsignedBigInteger('procesado_por')->nullable()->after('observaciones');
                $table->foreign('procesado_por')->references('id')->on('usuarios');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['procesado_por']);
            $table->dropColumn('procesado_por');
        });
    }
};