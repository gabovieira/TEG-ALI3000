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
        Schema::create('configuracion_fiscal', function (Blueprint $table) {
            $table->id();
            $table->decimal('iva_porcentaje', 5, 2);
            $table->decimal('islr_porcentaje', 5, 2);
            $table->date('fecha_vigencia');
            $table->boolean('activa')->default(true);
            $table->foreignId('creado_por')->constrained('usuarios');
            $table->timestamps();
            
            // Índices
            $table->index('activa');
            $table->index('fecha_vigencia');
            
            // Solo puede haber una configuración activa
            $table->unique('activa', 'unique_configuracion_activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_fiscal');
    }
};