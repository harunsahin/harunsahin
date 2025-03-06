<?php

namespace App\Services\Module;

use App\Services\Module\Exceptions\ModuleGenerationException;
use App\Services\Module\Handlers\{
    FileHandler,
    DatabaseHandler,
    SidebarHandler
};
use App\Services\Module\Results\ModuleGenerationResult;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ModuleGeneratorService
{
    protected $stateService;
    protected $fileHandler;
    protected $databaseHandler;
    protected $sidebarHandler;

    public function __construct(
        ModuleStateService $stateService,
        FileHandler $fileHandler,
        DatabaseHandler $databaseHandler,
        SidebarHandler $sidebarHandler
    ) {
        $this->stateService = $stateService;
        $this->fileHandler = $fileHandler;
        $this->databaseHandler = $databaseHandler;
        $this->sidebarHandler = $sidebarHandler;
    }

    public function generate(array $data): ModuleGenerationResult
    {
        try {
            // İşlem başlat
            $processId = uniqid('module_', true);
            $this->stateService->initialize($processId);

            // Temel değişkenleri hazırla
            $name = Str::studly($data['name']);
            $tableName = Str::snake(Str::plural($name));
            
            // Geçici dizin oluştur
            $tempDir = $this->fileHandler->createTempDirectory($processId);
            $this->stateService->update('temp_dir_created');

            try {
                // Dosyaları hazırla
                $files = $this->fileHandler->prepareFiles($name, $tableName, $data, $tempDir);
                $this->stateService->update('files_prepared');

                // Veritabanı işlemleri
                $this->databaseHandler->handle($name, $tableName, $files);
                $this->stateService->update('database_completed');
                
                // Dosyaları yerleştir
                $this->fileHandler->moveFiles($files);
                $this->stateService->update('files_moved');

                // Sidebar güncelle
                $this->sidebarHandler->handle($name);
                $this->stateService->update('sidebar_updated');

                // Başarılı sonuç döndür
                return new ModuleGenerationResult(
                    $name,
                    $processId,
                    $this->stateService->getDuration(),
                    $this->stateService->getCurrentState()
                );

            } catch (\Exception $e) {
                // Hata durumunda temizlik yap
                $this->fileHandler->cleanup($tempDir, $files ?? []);
                throw $e;
            }

        } catch (\Exception $e) {
            throw new ModuleGenerationException(
                'Modül oluşturma hatası: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
} 