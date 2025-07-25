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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->enum('tipo', ['whatsapp', 'email', 'sistema']);
            $table->enum('tipo_notificacion', [
                'pago_procesado', 
                'horas_aprobadas', 
                'horas_rechazadas', 
                'recordatorio_registro', 
                'tasa_bcv_actualizada', 
                'error_procesamiento'
            ]);
            $table->string('asunto', 255);
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'enviada', 'fallida', 'leida'])->default('pendiente');
            $table->json('datos_extra')->nullable();
            $table->timestamp('fecha_programada')->useCurrent();
            $table->timestamp('fecha_enviada')->nullable();
            $table->integer('intentos')->default(0);
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->index('usuario_id');
            $table->index('estado');
            $table->index('tipo_notificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
