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
        Schema::table('pago_detalles', function (Blueprint $table) {
            $table->decimal('iva', 10, 2)->nullable();
            $table->decimal('empresa_divisas', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_detalles', function (Blueprint $table) {
                $table->dropColumn('iva');
            $table->dropColumn('empresa_divisas');
        });
    }
};
