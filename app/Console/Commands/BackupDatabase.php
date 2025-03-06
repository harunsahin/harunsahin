<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Veritabanı yedeği oluşturur';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        $path = 'backups/' . $filename;

        try {
            // MySQL dump komutu
            $command = sprintf(
                'mysqldump -u%s -p%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                storage_path('app/' . $path)
            );

            exec($command);

            // S3'e yedekleme (isteğe bağlı)
            if (config('backup.use_s3')) {
                Storage::disk('s3')->put($path, Storage::disk('local')->get($path));
            }

            $this->info('Veritabanı yedeği başarıyla oluşturuldu: ' . $filename);
            return 0;
        } catch (\Exception $e) {
            $this->error('Yedekleme sırasında hata oluştu: ' . $e->getMessage());
            return 1;
        }
    }

    protected function addToZip($zip, $path, $relativePath)
    {
        if (!file_exists($path)) {
            return;
        }

        if (is_dir($path)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen(base_path()) + 1);
                    
                    $zip->addFile($filePath, $relativePath);
                }
            }
        } else {
            $zip->addFile($path, $relativePath);
        }
    }
} 