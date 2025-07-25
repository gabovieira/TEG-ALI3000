<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Campos base
            $table->bigInteger('usuario_id')->comment('Consultor que recibe el pago');
            $table->string('quincena')->comment('Período del pago (ej: 2025-01-Q1)');
            $table->decimal('total_horas', 10, 2)->comment('Suma de todas las horas del consultor');
            
            // Campos fiscales
            $table->decimal('iva_porcentaje', 5, 2);
            $table->decimal('islr_porcentaje', 5, 2);
            
            // Montos en divisas
            $table->decimal('ingreso_divisas', 15, 2)->comment('Total antes de impuestos');
            $table->decimal('monto_base_divisas', 15, 2);
            $table->decimal('iva_divisas', 15, 2);
            $table->decimal('total_con_iva_divisas', 15, 2);
            $table->decimal('islr_divisas', 15, 2);
            $table->decimal('total_menos_islr_divisas', 15, 2);
            
            // Tasa BCV
            $table->decimal('tasa_cambio', 10, 4);
            $table->date('fecha_tasa_bcv');
            
            // Montos en bolívares
            $table->decimal('monto_base_bs', 15, 2);
            $table->decimal('iva_bs', 15, 2);
            $table->decimal('total_con_iva_bs', 15, 2);
            $table->decimal('islr_bs', 15, 2);
            $table->decimal('total_menos_islr_bs', 15, 2);
            
            // Campos de estado y auditoría
            $table->enum('estado', ['pendiente', 'pagado', 'anulado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->bigInteger('procesado_por')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'quincena']);
            $table->index('estado');
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'usuario_id', 'quincena', 'total_horas',
                'iva_porcentaje', 'islr_porcentaje',
                'ingreso_divisas', 'monto_base_divisas', 'iva_divisas',
                'total_con_iva_divisas', 'islr_divisas', 'total_menos_islr_divisas',
                'tasa_cambio', 'fecha_tasa_bcv',
                'monto_base_bs', 'iva_bs', 'total_con_iva_bs',
                'islr_bs', 'total_menos_islr_bs',
                'estado', 'observaciones', 'fecha_pago', 'procesado_por'
            ]);
        });
    }
};
