<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desactivar verificaciones de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Primero, agregar las columnas que no requieren modificar restricciones
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
        });

        // Luego, en una operación separada, modificar el enum de estado
        if (Schema::hasColumn('pagos', 'estado')) {
            DB::statement("ALTER TABLE pagos MODIFY estado ENUM('pendiente', 'pagado', 'confirmado', 'rechazado', 'anulado') NOT NULL DEFAULT 'pendiente'");
        }

        // Reactivar verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
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