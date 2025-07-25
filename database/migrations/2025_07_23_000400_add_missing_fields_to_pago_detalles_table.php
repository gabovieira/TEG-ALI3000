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
            // Agregar campos que faltan
            if (!Schema::hasColumn('pago_detalles', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->nullable()->after('tarifa_por_hora');
            }
            if (!Schema::hasColumn('pago_detalles', 'tipo_moneda')) {
                $table->string('tipo_moneda', 3)->default('USD')->after('subtotal');
            }
            if (!Schema::hasColumn('pago_detalles', 'tasa_cambio')) {
                $table->decimal('tasa_cambio', 10, 4)->nullable()->after('tipo_moneda');
            }
            if (!Schema::hasColumn('pago_detalles', 'subtotal_bs')) {
                $table->decimal('subtotal_bs', 15, 2)->nullable()->after('tasa_cambio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'tipo_moneda',
                'tasa_cambio',
                'subtotal_bs'
            ]);
        });
    }
};