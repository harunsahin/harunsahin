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
        // Yorum değişiklikleri tablosu varsa verileri taşı
        if (Schema::hasTable('yorum_changes')) {
            // Yorum değişikliklerini activities tablosuna taşı
            $changes = DB::table('yorum_changes')->get();
            
            foreach ($changes as $change) {
                DB::table('activities')->insert([
                    'user_id' => $change->user_id,
                    'comment_id' => $change->comment_id,
                    'field' => $change->field,
                    'old_value' => json_encode($change->old_value),
                    'new_value' => json_encode($change->new_value),
                    'description' => 'Yorum değişikliği',
                    'subject_type' => 'App\Models\Comment',
                    'subject_id' => $change->comment_id,
                    'created_at' => $change->created_at,
                    'updated_at' => $change->updated_at
                ]);
            }

            // Yorum değişiklikleri tablosunu sil
            Schema::dropIfExists('yorum_changes');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Yorum değişiklikleri tablosunu geri oluştur
        Schema::create('yorum_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('comment_id')->constrained('yorums')->onDelete('cascade');
            $table->string('field');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->timestamps();
        });

        // Activities tablosundaki yorum değişikliklerini geri taşı
        $activities = DB::table('activities')->whereNotNull('comment_id')->get();
        
        foreach ($activities as $activity) {
            DB::table('yorum_changes')->insert([
                'user_id' => $activity->user_id,
                'comment_id' => $activity->comment_id,
                'field' => $activity->field,
                'old_value' => $activity->old_value,
                'new_value' => $activity->new_value,
                'created_at' => $activity->created_at,
                'updated_at' => $activity->updated_at
            ]);
        }
    }
}; 