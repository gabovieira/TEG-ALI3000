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
        Schema::create('feriados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique();
            $table->string('descripcion', 255)->nullable();
            $table->enum('tipo', ['nacional', 'regional', 'bancario'])->default('nacional');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('fecha');
            $table->index('tipo');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feriados');
    }
};
