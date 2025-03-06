<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Status;

class OfferSeeder extends Seeder
{
    public function run()
    {
        // Varsayılan durum ID'sini al
        $defaultStatusId = Status::where('name', 'Aktif')->first()->id ?? 1;

        // Otel veritabanından offers verilerini çek
        $offers = DB::connection('mysql')->select('SELECT * FROM otel.offers');

        foreach ($offers as $offer) {
            // Acenta ve firma ID'lerini bul
            $agency = Agency::where('name', $offer->agency)->first();
            $company = Company::where('name', $offer->company)->first();

            if (!$agency) {
                $agency = Agency::create([
                    'name' => $offer->agency,
                    'created_by' => 1,
                    'status_id' => $defaultStatusId,
                    'is_active' => true
                ]);
            }

            if (!$company) {
                $company = Company::create([
                    'name' => $offer->company,
                    'created_by' => 1,
                    'status_id' => $defaultStatusId,
                    'is_active' => true
                ]);
            }

            DB::table('offers')->insert([
                'agency_id' => $agency->id,
                'company_id' => $company->id,
                'full_name' => $offer->full_name ?? '',
                'phone' => $offer->phone ?? '',
                'email' => $offer->email ?? '',
                'checkin_date' => $offer->checkin_date ?? now(),
                'checkout_date' => $offer->checkout_date ?? now(),
                'room_count' => $offer->room_count ?? 1,
                'pax_count' => $offer->pax_count ?? 1,
                'option_date' => $offer->option_date ?? now(),
                'notes' => $offer->notes,
                'status_id' => $offer->status_id ?? $defaultStatusId,
                'created_by' => 1,
                'updated_by' => null,
                'is_active' => true,
                'created_at' => $offer->created_at ?? now(),
                'updated_at' => $offer->updated_at ?? now()
            ]);
        }
    }
} 