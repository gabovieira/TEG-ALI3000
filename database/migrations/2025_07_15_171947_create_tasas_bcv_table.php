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
        Schema::create('tasas_bcv', function (Blueprint $table) {
            $table->id();
            $table->decimal('tasa', 10, 2);
            $table->date('fecha_registro')->unique();
            $table->string('origen', 100)->default('API');
            $table->timestamp('fecha_creacion')->useCurrent();
            
            $table->index('fecha_registro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasas_bcv');
    }
};
