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
        // Desactivar verificaciones de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Primero verificamos si la columna empresa_id existe
        if (Schema::hasColumn('pagos', 'empresa_id')) {
            // Obtenemos todas las restricciones de clave foránea en la tabla
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_NAME = 'pagos' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
            );

            Schema::table('pagos', function (Blueprint $table) use ($constraints) {
                // Intentamos eliminar cualquier restricción que pueda estar relacionada con empresa_id
                foreach ($constraints as $constraint) {
                    try {
                        $table->dropForeign($constraint->CONSTRAINT_NAME);
                    } catch (\Exception $e) {
                        // Ignorar si no se puede eliminar la restricción
                        continue;
                    }
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

        // Reactivar verificaciones de claves foráneas
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
