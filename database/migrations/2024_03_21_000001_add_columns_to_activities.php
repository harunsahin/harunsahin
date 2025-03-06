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
            if (!Schema::hasColumn('activities', 'comment_id')) {
                $table->foreignId('comment_id')->nullable()->constrained('comments')->onDelete('cascade');
            }
            if (!Schema::hasColumn('activities', 'field')) {
                $table->string('field')->nullable();
            }
            if (!Schema::hasColumn('activities', 'old_value')) {
                $table->json('old_value')->nullable();
            }
            if (!Schema::hasColumn('activities', 'new_value')) {
                $table->json('new_value')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'comment_id')) {
                $table->dropForeign(['comment_id']);
                $table->dropColumn('comment_id');
            }
            if (Schema::hasColumn('activities', 'field')) {
                $table->dropColumn('field');
            }
            if (Schema::hasColumn('activities', 'old_value')) {
                $table->dropColumn('old_value');
            }
            if (Schema::hasColumn('activities', 'new_value')) {
                $table->dropColumn('new_value');
            }
        });
    }
}; 