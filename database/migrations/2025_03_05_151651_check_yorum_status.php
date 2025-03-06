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
        // Status alanını kontrol et ve güncelle
        DB::table('yorums')->whereNull('status')->update(['status' => true]);
        
        // Status değeri string olanları boolean'a çevir
        DB::table('yorums')
            ->where('status', 'active')
            ->update(['status' => true]);
            
        DB::table('yorums')
            ->where('status', 'inactive')
            ->update(['status' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alma işlemi gerekmez
    }
};
