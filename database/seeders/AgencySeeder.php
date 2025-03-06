<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        $agencies = [
            [
                'name' => 'Örnek Acente',
                'tax_number' => '0987654321',
                'tax_office' => 'Örnek Vergi Dairesi',
                'address' => 'Örnek Adres',
                'phone' => '0212 987 65 43',
                'email' => 'info@ornekacente.com',
                'website' => 'www.ornekacente.com',
                'status_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'is_active' => true,
                'created_at' => '2025-02-23 13:30:26',
                'updated_at' => '2025-02-25 10:08:46'
            ]
        ];

        foreach ($agencies as $agency) {
            Agency::create($agency);
        }
    }
} 