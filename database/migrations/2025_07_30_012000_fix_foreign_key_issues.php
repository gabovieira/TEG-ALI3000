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

        // Modificar la tabla pagos para solucionar problemas de restricciones
        Schema::table('pagos', function (Blueprint $table) {
            // Primero, intentamos eliminar cualquier restricción problemática
            $constraints = DB::select(
                "SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_NAME = 'pagos' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
            );

            foreach ($constraints as $constraint) {
                try {
                    $table->dropForeign($constraint->CONSTRAINT_NAME);
                } catch (\Exception $e) {
                    // Ignorar si no se puede eliminar la restricción
                }
            }

            // Modificar el enum de estado sin restricciones
            $table->enum('estado', ['pendiente', 'pagado', 'confirmado', 'rechazado', 'anulado'])
                  ->default('pendiente')
                  ->change();
        });

        // Reactivar verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario hacer nada en el down ya que estamos corrigiendo un problema
    }
};
