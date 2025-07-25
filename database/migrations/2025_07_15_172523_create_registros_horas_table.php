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
        Schema::create('registros_horas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('empresa_id');
            $table->date('fecha_trabajo');
            $table->text('actividades');
            $table->decimal('horas_normales', 3, 1)->default(0.0);
            $table->decimal('horas_extra', 3, 1)->default(0.0);
            $table->decimal('tiempo_descuento', 3, 1)->nullable()->default(0.0);
            $table->enum('tipo_descuento', ['llegada_tarde', 'falta', 'otro'])->nullable();
            $table->text('descripcion_descuento')->nullable();
            $table->enum('estado', ['en_espera', 'aprobada', 'rechazada'])->default('en_espera');
            $table->string('quincena_laboral', 20);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->index(['usuario_id', 'empresa_id']);
            $table->index('estado');
            $table->index('fecha_trabajo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_horas');
    }
};
