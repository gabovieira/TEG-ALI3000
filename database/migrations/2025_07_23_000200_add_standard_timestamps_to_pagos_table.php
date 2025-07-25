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
            // Agregar timestamps estándar de Laravel si no existen
            if (!Schema::hasColumn('pagos', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('datos_bancarios_id');
            }
            if (!Schema::hasColumn('pagos', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};