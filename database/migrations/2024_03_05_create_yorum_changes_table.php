<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('yorum_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yorum_id')->constrained('yorums')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('yorum_changes');
    }
}; 