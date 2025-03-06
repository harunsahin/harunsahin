<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Status alanını integer olarak güncelle
        DB::statement("ALTER TABLE yorums MODIFY COLUMN status TINYINT(1) DEFAULT 1");
        
        // Tüm status değerlerini 1 yap
        DB::statement("UPDATE yorums SET status = 1 WHERE status IS NULL OR status = 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi gerekmez
    }
};
