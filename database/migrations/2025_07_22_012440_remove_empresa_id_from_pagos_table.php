<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, desactivamos temporalmente las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Primero verificamos si la columna empresa_id existe
        if (Schema::hasColumn('pagos', 'empresa_id')) {
            // Verificamos si existe la restricción de clave foránea
            $constraint = DB::select(
                "SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'pagos' 
                AND COLUMN_NAME = 'empresa_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL"
            );

            Schema::table('pagos', function (Blueprint $table) use ($constraint) {
                // Si existe la restricción, la eliminamos
                if (count($constraint) > 0) {
                    $table->dropForeign([$constraint[0]->CONSTRAINT_NAME]);
                }

                // Luego intentamos eliminar el índice compuesto si existe
                try {
                    $table->dropIndex('pagos_usuario_id_empresa_id_index');
                } catch (\Exception $e) {
                    // Ignorar si el índice no existe
                }

                // Finalmente eliminamos la columna
                $table->dropColumn('empresa_id');
            });
        }

        // Reactivamos las verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
