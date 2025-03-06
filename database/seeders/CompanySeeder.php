<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        // Otel veritabanından companies verilerini çek
        $companies = DB::connection('mysql')->select('SELECT * FROM otel.companies');

        foreach ($companies as $company) {
            DB::table('companies')->insert([
                'name' => $company->name ?? '',
                'tax_number' => $company->tax_number ?? null,
                'tax_office' => $company->tax_office ?? null,
                'address' => $company->address ?? null,
                'phone' => $company->phone ?? null,
                'email' => $company->email ?? null,
                'website' => $company->website ?? null,
                'status_id' => $company->status_id ?? 5,
                'created_by' => 1,
                'updated_by' => null,
                'is_active' => true,
                'created_at' => $company->created_at ?? now(),
                'updated_at' => $company->updated_at ?? now()
            ]);
        }
    }
} 