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
        Schema::table('pago_detalles', function (Blueprint $table) {
            // Hacer que monto_empresa_divisas permita valores NULL
            $table->decimal('monto_empresa_divisas', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_detalles', function (Blueprint $table) {
            // Revertir para que no permita NULL
            $table->decimal('monto_empresa_divisas', 10, 2)->nullable(false)->change();
        });
    }
};