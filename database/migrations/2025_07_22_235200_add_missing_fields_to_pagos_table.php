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
            // Agregar campos que faltan para el nuevo diseño
            if (!Schema::hasColumn('pagos', 'monto_total')) {
                $table->decimal('monto_total', 15, 2)->nullable()->after('tarifa_por_hora');
            }
            if (!Schema::hasColumn('pagos', 'iva_monto')) {
                $table->decimal('iva_monto', 15, 2)->nullable()->after('iva_porcentaje');
            }
            if (!Schema::hasColumn('pagos', 'islr_monto')) {
                $table->decimal('islr_monto', 15, 2)->nullable()->after('islr_porcentaje');
            }
            if (!Schema::hasColumn('pagos', 'monto_neto')) {
                $table->decimal('monto_neto', 15, 2)->nullable()->after('islr_monto');
            }
            if (!Schema::hasColumn('pagos', 'referencia_bancaria')) {
                $table->string('referencia_bancaria')->nullable()->after('fecha_pago');
            }
            
            // Modificar el enum de estado para incluir los nuevos estados
            $table->enum('estado', ['pendiente', 'pagado', 'confirmado', 'rechazado', 'anulado'])->default('pendiente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'monto_total',
                'iva_monto', 
                'islr_monto',
                'monto_neto',
                'referencia_bancaria'
            ]);
            
            // Revertir el enum de estado
            $table->enum('estado', ['pendiente', 'pagado', 'anulado'])->default('pendiente')->change();
        });
    }
};