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
        Schema::create('tarifa_consultores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->decimal('tarifa_por_hora', 10, 2);
            $table->enum('moneda', ['USD', 'EUR'])->default('USD');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->foreignId('creado_por')->constrained('usuarios');
            $table->timestamps();
            
            // Índices
            $table->index(['usuario_id', 'empresa_id']);
            $table->index('activa');
            $table->index('fecha_inicio');
            
            // Constraint único para evitar tarifas duplicadas activas
            $table->unique(['usuario_id', 'empresa_id', 'activa'], 'unique_tarifa_activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifa_consultores');
    }
};