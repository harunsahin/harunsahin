<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            AgencySeeder::class,
            CompanySeeder::class,
            OfferSeeder::class,
            OfferFileSeeder::class,
        ]);
    }
} 