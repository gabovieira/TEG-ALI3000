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
            if (!Schema::hasColumn('pago_detalles', 'iva_empresa_divisas')) {
                $table->decimal('iva_empresa_divisas', 15, 2)->nullable()->after('monto_empresa_divisas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_detalles', function (Blueprint $table) {
            if (Schema::hasColumn('pago_detalles', 'iva_empresa_divisas')) {
                $table->dropColumn('iva_empresa_divisas');
            }
        });
    }
};
