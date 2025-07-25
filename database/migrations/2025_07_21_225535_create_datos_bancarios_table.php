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
        Schema::create('datos_bancarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('banco', 100);
            $table->enum('tipo_cuenta', ['ahorro', 'corriente']);
            $table->string('numero_cuenta', 30);
            $table->string('cedula_rif', 20);
            $table->string('titular', 100);
            $table->boolean('es_principal')->default(true);
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_bancarios');
    }
};
