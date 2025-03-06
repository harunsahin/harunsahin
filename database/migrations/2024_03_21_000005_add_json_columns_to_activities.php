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
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'old_values')) {
                $table->json('old_values')->nullable()->after('new_value');
            }
            if (!Schema::hasColumn('activities', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['old_values', 'new_values']);
        });
    }
}; 