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
        Schema::table('registro_horas', function (Blueprint $table) {
            $table->decimal('tiempo_descuento', 3, 1)->nullable()->default(0.0)->after('descripcion_actividades');
            $table->enum('tipo_descuento', ['llegada_tarde', 'falta', 'otro'])->nullable()->after('tiempo_descuento');
            $table->text('descripcion_descuento')->nullable()->after('tipo_descuento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_horas', function (Blueprint $table) {
            $table->dropColumn(['tiempo_descuento', 'tipo_descuento', 'descripcion_descuento']);
        });
    }
};