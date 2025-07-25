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
        // Primero eliminamos la restricción de clave foránea
        Schema::table('pagos', function (Blueprint $table) {
            // Verificamos si la restricción existe antes de intentar eliminarla
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['empresa_id']);
            }
            
            // Eliminamos el índice compuesto si existe
            $table->dropIndex(['usuario_id', 'empresa_id']);
            
            // Finalmente eliminamos la columna
            $table->dropColumn('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Volvemos a agregar la columna empresa_id
            $table->unsignedBigInteger('empresa_id')->after('usuario_id');
            
            // Agregamos la clave foránea
            $table->foreign('empresa_id')
                  ->references('id')
                  ->on('empresas')
                  ->onDelete('cascade');
                  
            // Agregamos el índice compuesto
            $table->index(['usuario_id', 'empresa_id']);
        });
    }
};
