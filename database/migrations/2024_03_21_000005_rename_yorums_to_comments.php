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
        // Önce comments tablosunu sil
        Schema::dropIfExists('comments');

        // Yeni tabloyu oluştur
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->dateTime('comment_date');
            $table->boolean('status')->default(true);
            $table->integer('position')->nullable();
            $table->string('source')->default('Tripadvisor');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Verileri yeni tabloya taşı
        $yorums = DB::table('yorums')->get();
        foreach ($yorums as $yorum) {
            DB::table('comments')->insert([
                'id' => $yorum->id,
                'name' => $yorum->name,
                'content' => $yorum->content,
                'comment_date' => $yorum->comment_date,
                'status' => $yorum->status,
                'position' => $yorum->position,
                'source' => $yorum->source ?? 'Tripadvisor',
                'created_by' => $yorum->created_by,
                'updated_by' => $yorum->updated_by,
                'created_at' => $yorum->created_at,
                'updated_at' => $yorum->updated_at,
                'deleted_at' => $yorum->deleted_at
            ]);
        }

        // Eski tabloyu sil
        Schema::dropIfExists('yorums');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eski tabloyu geri oluştur
        Schema::create('yorums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->dateTime('comment_date');
            $table->boolean('status')->default(true);
            $table->integer('position')->nullable();
            $table->string('source')->default('Tripadvisor');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Verileri eski tabloya geri taşı
        $comments = DB::table('comments')->get();
        foreach ($comments as $comment) {
            DB::table('yorums')->insert([
                'id' => $comment->id,
                'name' => $comment->name,
                'content' => $comment->content,
                'comment_date' => $comment->comment_date,
                'status' => $comment->status,
                'position' => $comment->position,
                'source' => $comment->source ?? 'Tripadvisor',
                'created_by' => $comment->created_by,
                'updated_by' => $comment->updated_by,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
                'deleted_at' => $comment->deleted_at
            ]);
        }

        // Yeni tabloyu sil
        Schema::dropIfExists('comments');
    }
}; 