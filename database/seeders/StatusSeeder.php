<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        // Otel veritabanından statuses verilerini al
        $statuses = DB::connection('mysql')->table('otel.statuses')->get();

        // Verileri yeni veritabanına aktar
        foreach ($statuses as $status) {
            DB::table('statuses')->insert([
                'id' => $status->id,
                'name' => $status->name,
                'slug' => $status->slug,
                'color' => $status->color,
                'icon' => $status->icon,
                'type' => $status->type,
                'order' => $status->order,
                'is_active' => $status->is_active,
                'created_at' => $status->created_at,
                'updated_at' => $status->updated_at
            ]);
        }
    }
} 