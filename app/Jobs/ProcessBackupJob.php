<?php

namespace App\Jobs;

use App\Events\BackupCompleted;
use App\Events\BackupFailed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use ZipArchive;

class ProcessBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $backupName;
    protected $type;
    protected $description;
    protected $compress;
    protected $notify;

    public function __construct($userId, $backupName, $type, $description = null, $compress = true, $notify = true)
    {
        $this->userId = $userId;
        $this->backupName = $backupName;
        $this->type = $type;
        $this->description = $description;
        $this->compress = $compress;
        $this->notify = $notify;
    }

    public function handle()
    {
        try {
            $backupFiles = [];

            // Veritabanı yedeği
            if (in_array($this->type, ['full', 'database'])) {
                $dbFile = $this->backupDatabase();
                if ($dbFile) {
                    $backupFiles[] = $dbFile;
                }
            }

            // Dosya yedeği
            if (in_array($this->type, ['full', 'files'])) {
                $filesArchive = $this->backupFiles();
                if ($filesArchive) {
                    $backupFiles[] = $filesArchive;
                }
            }

            // Sıkıştırma
            if ($this->compress && count($backupFiles) > 0) {
                $finalArchive = $this->compressBackupFiles($backupFiles);
                
                // Geçici dosyaları temizle
                foreach ($backupFiles as $file) {
                    Storage::disk('backups')->delete($file);
                }
                
                $backupFiles = [$finalArchive];
            }

            // Bildirim gönder
            if ($this->notify) {
                event(new BackupCompleted($this->userId, $backupFiles));
            }

        } catch (\Exception $e) {
            event(new BackupFailed($this->userId, $e->getMessage()));
            throw $e;
        }
    }

    protected function backupDatabase()
    {
        $filename = $this->backupName . '-database.sql';
        $tempFile = storage_path('app/backups/' . $filename);

        // MySQL dump komutu
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $tempFile
        );

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Veritabanı yedeği alınırken bir hata oluştu: ' . $process->getErrorOutput());
        }

        // Dosyayı backups diskine taşı
        Storage::disk('backups')->put($filename, file_get_contents($tempFile));
        unlink($tempFile);

        return $filename;
    }

    protected function backupFiles()
    {
        $filename = $this->backupName . '-files.zip';
        $zip = new ZipArchive();

        if ($zip->open(storage_path('app/backups/' . $filename), ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Yedeklenecek dizinler
            $directories = [
                'app' => base_path('app'),
                'config' => base_path('config'),
                'database' => base_path('database'),
                'public' => base_path('public'),
                'resources' => base_path('resources'),
                'routes' => base_path('routes'),
                'storage/app/public' => storage_path('app/public'),
            ];

            foreach ($directories as $name => $path) {
                if (is_dir($path)) {
                    $this->addDirectoryToZip($zip, $path, $name);
                }
            }

            $zip->close();

            // Dosyayı backups diskine taşı
            Storage::disk('backups')->put($filename, file_get_contents(storage_path('app/backups/' . $filename)));
            unlink(storage_path('app/backups/' . $filename));

            return $filename;
        }

        throw new \Exception('Dosya yedeği oluşturulurken bir hata oluştu.');
    }

    protected function addDirectoryToZip($zip, $path, $relativePath)
    {
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
    }

    protected function compressBackupFiles($files)
    {
        $filename = $this->backupName . '.zip';
        $zip = new ZipArchive();

        if ($zip->open(storage_path('app/backups/' . $filename), ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $zip->addFile(
                    storage_path('app/backups/' . $file),
                    basename($file)
                );
            }

            $zip->close();

            // Dosyayı backups diskine taşı
            Storage::disk('backups')->put($filename, file_get_contents(storage_path('app/backups/' . $filename)));
            unlink(storage_path('app/backups/' . $filename));

            return $filename;
        }

        throw new \Exception('Yedekleme dosyaları sıkıştırılırken bir hata oluştu.');
    }
}
