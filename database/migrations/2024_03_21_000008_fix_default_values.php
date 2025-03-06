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
            $table->string('subject_type')->nullable()->change();
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->text('message')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('subject_type')->nullable(false)->change();
        });

        Schema::table('logs', function (Blueprint $table) {
            $table->text('message')->nullable(false)->change();
        });
    }
}; 