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
        // Alan isimlerini güncelle
        Schema::table('yorums', function (Blueprint $table) {
            $table->renameColumn('adisoyadi', 'name');
            $table->renameColumn('yorum', 'content');
            $table->renameColumn('yorumtarihi', 'comment_date');
            $table->renameColumn('kaynak', 'source');
        });

        // Alan isimlerini güncelle
        Schema::table('yorum_changes', function (Blueprint $table) {
            $table->renameColumn('yorum_id', 'comment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Alan isimlerini geri al
        Schema::table('yorum_changes', function (Blueprint $table) {
            $table->renameColumn('comment_id', 'yorum_id');
        });

        // Alan isimlerini geri al
        Schema::table('yorums', function (Blueprint $table) {
            $table->renameColumn('name', 'adisoyadi');
            $table->renameColumn('content', 'yorum');
            $table->renameColumn('comment_date', 'yorumtarihi');
            $table->renameColumn('source', 'kaynak');
        });
    }
}; 