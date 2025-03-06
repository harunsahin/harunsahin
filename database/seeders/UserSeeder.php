<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // Super Admin
                'is_active' => true,
                'created_at' => '2025-02-23 13:30:16',
                'updated_at' => '2025-02-23 13:30:16'
            ],
            [
                'id' => 2,
                'name' => 'HARUN ŞAHİN',
                'email' => 'harun.sahin@hotmail.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // Admin
                'is_active' => true,
                'created_at' => '2025-02-23 13:49:50',
                'updated_at' => '2025-02-23 13:49:50'
            ],
            [
                'id' => 3,
                'name' => 'SELİN ŞAHİN',
                'email' => 'selinsahin@outlook.com.tr',
                'password' => Hash::make('password'),
                'role_id' => 3, // Manager
                'is_active' => true,
                'created_at' => '2025-02-24 16:40:41',
                'updated_at' => '2025-02-24 16:40:41'
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
} 