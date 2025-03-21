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
            $table->foreignId('comment_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->string('field')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn(['comment_id', 'field', 'old_value', 'new_value']);
        });
    }
}; 