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
        Schema::dropIfExists('yorum_changes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('yorum_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('comment_id')->constrained('yorums')->onDelete('cascade');
            $table->string('field');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->timestamps();
        });
    }
}; 