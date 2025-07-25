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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('quincena', 20);
            $table->decimal('horas', 5, 1);
            $table->decimal('tarifa_por_hora', 8, 2);
            $table->decimal('iva_porcentaje', 4, 2);
            $table->decimal('islr_porcentaje', 4, 2);
            $table->decimal('ingreso_divisas', 10, 2);
            $table->decimal('monto_base_divisas', 10, 2);
            $table->decimal('iva_divisas', 10, 2);
            $table->decimal('total_con_iva_divisas', 10, 2);
            $table->decimal('tasa_cambio', 10, 4);
            $table->date('fecha_tasa_bcv');
            $table->decimal('monto_base_bs', 14, 2);
            $table->decimal('iva_bs', 14, 2);
            $table->decimal('total_con_iva_bs', 14, 2);
            $table->decimal('islr_divisas', 10, 2);
            $table->decimal('total_menos_islr_divisas', 10, 2);
            $table->decimal('islr_bs', 14, 2);
            $table->decimal('total_menos_islr_bs', 14, 2);
            $table->date('fecha_pago');
            $table->enum('estado', ['pendiente', 'pagado', 'anulado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('procesado_por');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('procesado_por')->references('id')->on('usuarios');
            $table->index(['usuario_id', 'empresa_id']);
            $table->index('estado');
            $table->index('fecha_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
