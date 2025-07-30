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
            // Asegurarnos de que las columnas necesarias existan
            if (!Schema::hasColumn('pagos', 'monto_neto')) {
                $table->decimal('monto_neto', 15, 2)->nullable()->after('total_menos_islr_bs');
            }
            
            if (!Schema::hasColumn('pagos', 'monto_total')) {
                $table->decimal('monto_total', 15, 2)->nullable()->after('tarifa_por_hora');
            }
            
            if (!Schema::hasColumn('pagos', 'iva_monto')) {
                $table->decimal('iva_monto', 15, 2)->nullable()->after('iva_porcentaje');
            }
            
            if (!Schema::hasColumn('pagos', 'islr_monto')) {
                $table->decimal('islr_monto', 15, 2)->nullable()->after('islr_porcentaje');
            }
            
            if (!Schema::hasColumn('pagos', 'referencia_bancaria')) {
                $table->string('referencia_bancaria')->nullable()->after('fecha_pago');
            }
            
            // Asegurarnos de que el enum de estado tenga los valores correctos
            $table->enum('estado', ['pendiente', 'pagado', 'confirmado', 'rechazado', 'anulado'])->default('pendiente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario hacer nada en el down ya que estamos asegurando que las columnas existan
    }
};
