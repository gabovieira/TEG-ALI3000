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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_usuario', ['admin', 'consultor']);
            $table->string('primer_nombre', 50);
            $table->string('primer_apellido', 50);
            $table->string('email', 100)->unique();
            $table->string('password_hash', 255)->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'pendiente_registro'])->default('pendiente_registro');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('creado_por')->nullable();
            
            $table->index('email');
            $table->index('tipo_usuario');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
