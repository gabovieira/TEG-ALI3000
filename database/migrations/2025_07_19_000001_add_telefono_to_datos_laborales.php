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
        Schema::table('datos_laborales', function (Blueprint $table) {
            $table->string('telefono_personal', 20)->nullable()->after('nivel_desarrollo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_laborales', function (Blueprint $table) {
            $table->dropColumn('telefono_personal');
        });
    }
};