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
        // Yeni sütunları ekle
        Schema::table('offers', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
        });

        // Verileri yeni sütunlara aktar
        DB::statement("
            UPDATE offers o
            INNER JOIN agencies a ON a.name = o.agency
            SET o.agency_id = a.id
        ");

        DB::statement("
            UPDATE offers o
            INNER JOIN companies c ON c.name = o.company
            SET o.company_id = c.id
        ");

        // Eski sütunları kaldır
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['agency', 'company']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eski sütunları geri ekle
        Schema::table('offers', function (Blueprint $table) {
            $table->string('agency')->nullable();
            $table->string('company')->nullable();
        });

        // Verileri eski sütunlara kopyala
        DB::statement('
            UPDATE offers o
            INNER JOIN agencies a ON a.id = o.agency_id
            SET o.agency = a.name
        ');

        DB::statement('
            UPDATE offers o
            INNER JOIN companies c ON c.id = o.company_id
            SET o.company = c.name
        ');

        // Yeni sütunları kaldır
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['company_id']);
            $table->dropColumn(['agency_id', 'company_id']);
        });
    }
};
