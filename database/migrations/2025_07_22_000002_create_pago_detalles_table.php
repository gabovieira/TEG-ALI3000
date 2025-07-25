<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pago_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pagos')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->decimal('horas', 10, 2);
            $table->decimal('tarifa_por_hora', 10, 2);
            $table->decimal('monto_empresa_divisas', 15, 2);
            $table->decimal('iva_empresa_divisas', 15, 2);
            $table->decimal('islr_empresa_divisas', 15, 2);
            $table->decimal('total_empresa_divisas', 15, 2);
            $table->timestamps();
            
            // Índices
            $table->index(['pago_id', 'empresa_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pago_detalles');
    }
};
