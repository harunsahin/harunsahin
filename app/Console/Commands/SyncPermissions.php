<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PermissionService;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync';
    protected $description = 'Tüm izinleri senkronize eder';

    public function handle()
    {
        $this->info('İzinler senkronize ediliyor...');
        
        try {
            PermissionService::syncPermissions();
            $this->info('İzinler başarıyla senkronize edildi!');
        } catch (\Exception $e) {
            $this->error('Hata oluştu: ' . $e->getMessage());
        }
    }
} 