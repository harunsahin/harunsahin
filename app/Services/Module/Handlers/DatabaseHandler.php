<?php

namespace App\Services\Module\Handlers;

use App\Services\Module\Exceptions\ModuleGenerationException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseHandler
{
    public function handle(string $name, string $tableName, array $files): void
    {
        try {
            // Foreign key kontrollerini kapat
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Eğer tablo varsa önce sil
            if (Schema::hasTable($tableName)) {
                Schema::dropIfExists($tableName);
            }

            // Migration dosyasını taşı
            File::move(
                $files['migration']['temp'],
                $files['migration']['final']
            );

            // Migration'ı çalıştır
            $exitCode = Artisan::call('migrate', [
                '--path' => "database/migrations/" . basename($files['migration']['final']),
                '--force' => true,
                '--no-interaction' => true
            ]);
            
            if ($exitCode !== 0) {
                throw new ModuleGenerationException(
                    'Migration hatası: ' . Artisan::output()
                );
            }

            // Sadece ilgili tablo için seeder'ı çalıştır
            $seederClass = Str::studly(Str::singular($tableName)) . 'Seeder';
            if (class_exists("Database\\Seeders\\{$seederClass}")) {
                $seederExitCode = Artisan::call('db:seed', [
                    '--class' => "Database\\Seeders\\{$seederClass}",
                    '--force' => true,
                    '--no-interaction' => true
                ]);

                if ($seederExitCode !== 0) {
                    throw new ModuleGenerationException(
                        'Seeder hatası: ' . Artisan::output()
                    );
                }
            }

            \Log::info('Veritabanı işlemleri tamamlandı', [
                'table' => $tableName,
                'migration' => basename($files['migration']['final']),
                'seeder' => isset($seederClass) ? $seederClass : null
            ]);

        } catch (\Exception $e) {
            throw new ModuleGenerationException('Veritabanı işlemi hatası: ' . $e->getMessage());
        } finally {
            // Her durumda foreign key kontrollerini geri aç
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
} 