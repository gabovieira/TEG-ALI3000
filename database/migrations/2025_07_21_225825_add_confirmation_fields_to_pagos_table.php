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
        Schema::table('pagos', function (Blueprint $table) {
            // Solo agregar las columnas que no existen
            if (!Schema::hasColumn('pagos', 'fecha_procesado')) {
                $table->timestamp('fecha_procesado')->nullable();
            }
            if (!Schema::hasColumn('pagos', 'fecha_confirmacion')) {
                $table->timestamp('fecha_confirmacion')->nullable();
            }
            if (!Schema::hasColumn('pagos', 'comentario_confirmacion')) {
                $table->text('comentario_confirmacion')->nullable();
            }
            if (!Schema::hasColumn('pagos', 'comprobante_pago')) {
                $table->string('comprobante_pago')->nullable();
            }
            if (!Schema::hasColumn('pagos', 'datos_bancarios_id')) {
                $table->unsignedBigInteger('datos_bancarios_id')->nullable();
                $table->foreign('datos_bancarios_id')->references('id')->on('datos_bancarios');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['procesado_por']);
            $table->dropForeign(['datos_bancarios_id']);
            
            $table->dropColumn([
                'fecha_procesado',
                'procesado_por',
                'fecha_confirmacion',
                'comentario_confirmacion',
                'comprobante_pago',
                'datos_bancarios_id'
            ]);
        });
    }
};