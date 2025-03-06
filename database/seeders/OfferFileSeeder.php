<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferFileSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('offer_files')->insert([
            [
                'offer_id' => 6,
                'original_name' => 'ornek_teklif.pdf',
                'file_path' => 'offer-files/ornek_teklif.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 1024 * 1024, // 1MB
                'created_by' => 1,
                'updated_by' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
} 