<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupService
{
    private $backupablePaths = [
        'root_files' => [
            'path' => '/',
            'description' => 'Ana dizin dosyaları (.env, composer.json vb.)'
        ],
        'app' => [
            'path' => 'app',
            'description' => 'Uygulama kaynak kodları'
        ],
        'bootstrap' => [
            'path' => 'bootstrap',
            'description' => 'Framework başlangıç dosyaları'
        ],
        'config' => [
            'path' => 'config',
            'description' => 'Yapılandırma dosyaları'
        ],
        'database' => [
            'path' => 'database',
            'description' => 'Veritabanı migration ve seed dosyaları'
        ],
        'public' => [
            'path' => 'public',
            'description' => 'Genel erişime açık dosyalar'
        ],
        'resources' => [
            'path' => 'resources',
            'description' => 'View ve dil dosyaları'
        ],
        'routes' => [
            'path' => 'routes',
            'description' => 'Route tanımlamaları'
        ],
        'storage' => [
            'path' => 'storage',
            'description' => 'Uygulama depolama alanı'
        ],
        'vendor' => [
            'path' => 'vendor',
            'description' => 'Composer paketleri'
        ]
    ];

    public function getBackups()
    {
        $backupPath = storage_path('app/backups');
        $files = scandir($backupPath);
        $backups = [];

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $fullPath = $backupPath . '/' . $file;
                $backups[] = [
                    'name' => $file,
                    'size' => filesize($fullPath),
                    'created_at' => Carbon::createFromTimestamp(filemtime($fullPath))
                ];
            }
        }

        return collect($backups)
            ->sortByDesc('created_at')
            ->values()
            ->all();
    }

    public function getBackupData()
    {
        // Veritabanı tablolarını al
        $tables = DB::select('SHOW TABLE STATUS');
        $tableData = [];
        
        foreach ($tables as $table) {
            $tableData[] = [
                'name' => $table->Name,
                'rows' => $table->Rows,
                'size' => ($table->Data_length + $table->Index_length) / 1024 / 1024
            ];
        }

        // Dizinleri hazırla
        $directories = [];
        foreach ($this->backupablePaths as $key => $info) {
            $path = base_path($info['path']);
            $exists = file_exists($path) && is_dir($path);
            
            $directories[] = [
                'path' => $info['path'],
                'description' => $info['description'],
                'exists' => $exists,
                'files' => $exists ? $this->countFiles($path) : 0,
                'size' => $exists ? $this->getDirSize($path) : 0
            ];
        }

        return [
            'tableData' => $tableData,
            'directories' => $directories,
            'backupLocations' => [
                'default' => 'Varsayılan (storage/app/backups)',
                'custom' => 'Özel Konum'
            ]
        ];
    }

    public function createBackup(array $data)
    {
        // Yedekleme konumunu belirle
        $backupPath = $data['backup_location'] === 'custom' 
            ? $data['custom_path'] 
            : storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $dbBackupName = 'backup-db-' . $timestamp . '.sql';
        $filesBackupName = 'backup-files-' . $timestamp . '.zip';
        
        $dbBackupPath = $backupPath . '/' . $dbBackupName;
        $filesBackupPath = $backupPath . '/' . $filesBackupName;

        $backupFiles = [];

        // Veritabanı yedeği
        if (!empty($data['tables'])) {
            $this->backupDatabase($data['tables'], $dbBackupPath);
            $backupFiles['db_backup'] = $dbBackupName;
        }
        
        // Dosya yedeği
        if (!empty($data['directories'])) {
            $this->backupFiles($data['directories'], $filesBackupPath);
            $backupFiles['files_backup'] = $filesBackupName;
        }

        return $backupFiles;
    }

    private function backupDatabase($selectedTables, $fullPath)
    {
        $handle = fopen($fullPath, 'w');

        foreach ($selectedTables as $table) {
            // Tablo yapısını yedekle
            $createTable = DB::select('SHOW CREATE TABLE ' . $table)[0];
            fwrite($handle, "\nDROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createTable->{'Create Table'} . ";\n\n");

            // Tablo verilerini yedekle
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $values = array_map(function($value) {
                    return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                }, (array) $row);

                fwrite($handle, "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n");
            }
        }

        fclose($handle);
    }

    private function backupFiles($selectedDirectories, $fullPath)
    {
        $zip = new ZipArchive();
        $zip->open($fullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($selectedDirectories as $directory) {
            $sourcePath = base_path($directory);
            if (file_exists($sourcePath)) {
                $this->addFolderToZip($zip, $sourcePath, $directory);
            }
        }

        $zip->close();
    }

    private function addFolderToZip($zip, $sourcePath, $folderName)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $folderName . '/' . substr($filePath, strlen($sourcePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    public function deleteBackup($backup)
    {
        $backupPath = storage_path('app/backups/' . $backup);
        if (file_exists($backupPath)) {
            unlink($backupPath);
            return true;
        }
        return false;
    }

    public function downloadBackup($backup)
    {
        $backupPath = storage_path('app/backups/' . $backup);
        if (file_exists($backupPath)) {
            return response()->download($backupPath);
        }
        return null;
    }

    private function countFiles($directory)
    {
        $count = 0;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $count++;
            }
        }

        return $count;
    }

    private function getDirSize($directory)
    {
        $size = 0;
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
} 