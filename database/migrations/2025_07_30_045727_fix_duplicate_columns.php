<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Check if columns exist before adding them
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
            
            // Update enum values if needed
            if (Schema::hasColumn('pagos', 'estado')) {
                $table->enum('estado', ['pendiente', 'pagado', 'confirmado', 'rechazado', 'anulado'])
                      ->default('pendiente')
                      ->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // We don't need to do anything in the down method
        // as this is a fix migration
    }
};
