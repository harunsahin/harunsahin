<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'name' => 'Test Kullanıcı',
            'content' => 'Bu bir test yorumudur.',
            'comment_date' => now(),
            'status' => true,
            'source' => 'Tripadvisor'
        ]);
    }
} 