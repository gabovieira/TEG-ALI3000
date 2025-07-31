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
            $table->decimal('iva_empresa_divisas', 15, 2)->nullable()->change();
            $table->decimal('islr_empresa_divisas', 15, 2)->nullable()->change();
            $table->decimal('total_empresa_divisas', 15, 2)->nullable()->change();
            $table->decimal('iva_empresa_divisas', 15, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_detalles', function (Blueprint $table) {
            $table->decimal('iva_empresa_divisas', 15, 2)->nullable(false)->change();
            $table->decimal('islr_empresa_divisas', 15, 2)->nullable(false)->change();
            $table->decimal('total_empresa_divisas', 15, 2)->nullable(false)->change();
            $table->decimal('iva_empresa_divisas', 15, 2)->nullable(false)->change();
        });
    }
};
