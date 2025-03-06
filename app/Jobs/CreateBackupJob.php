<?php

namespace App\Jobs;

use App\Services\BackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $backupService;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->backupService = app(BackupService::class);
    }

    public function handle()
    {
        try {
            // Yedekleme işlemini başlat
            $backupFiles = $this->backupService->createBackup($this->data);
            
            // İşlem başarılı olduğunda session'ı güncelle
            session(['backup_status' => [
                'progress' => 100,
                'message' => 'Yedekleme tamamlandı!',
                'can_cancel' => false
            ]]);

            Log::info('Yedekleme başarıyla tamamlandı:', $backupFiles);
        } catch (\Exception $e) {
            Log::error('Yedekleme işlemi başarısız:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session(['backup_status' => [
                'progress' => 0,
                'message' => 'Yedekleme başarısız: ' . $e->getMessage(),
                'can_cancel' => false
            ]]);
        } finally {
            session(['backup_in_progress' => false]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Yedekleme işlemi başarısız:', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        session(['backup_status' => [
            'progress' => 0,
            'message' => 'Yedekleme başarısız: ' . $exception->getMessage(),
            'can_cancel' => false
        ]]);
        session(['backup_in_progress' => false]);
    }
} 