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
        Schema::table('tickets', function (Blueprint $table) {
            // Vérifiez si la colonne existe déjà pour éviter les erreurs
            if (!Schema::hasColumn('tickets', 'priority')) {
                $table->string('priority')->default('medium');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'priority')) {
                $table->dropColumn('priority');
            }
        });
    }
};
