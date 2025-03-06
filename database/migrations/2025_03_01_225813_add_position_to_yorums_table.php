<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Yorum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('yorums', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('yorumtarihi');
        });

        // Mevcut kayıtlara sıra numarası ver
        $yorums = Yorum::orderBy('created_at')->get();
        foreach ($yorums as $index => $yorum) {
            $yorum->position = $index + 1;
            $yorum->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yorums', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
