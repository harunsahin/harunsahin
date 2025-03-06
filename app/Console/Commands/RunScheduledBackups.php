<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BackupSchedule;
use App\Jobs\ProcessBackupJob;
use Carbon\Carbon;

class RunScheduledBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backups:run-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Planlı yedeklemeleri çalıştır';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Çalıştırılması gereken yedeklemeleri bul
        $schedules = BackupSchedule::where('is_active', true)
            ->where('next_run', '<=', $now)
            ->get();

        foreach ($schedules as $schedule) {
            try {
                // Yedekleme işini kuyruğa ekle
                ProcessBackupJob::dispatch(
                    $schedule->created_by,
                    $schedule->name,
                    $schedule->type,
                    $schedule->description,
                    $schedule->compress,
                    $schedule->notify
                );

                // Son çalıştırma zamanını güncelle
                $schedule->last_run = $now;
                $schedule->updateNextRun();
                
                $this->info("Yedekleme başlatıldı: {$schedule->name}");
            } catch (\Exception $e) {
                $this->error("Yedekleme hatası ({$schedule->name}): " . $e->getMessage());
                \Log::error("Planlı yedekleme hatası: " . $e->getMessage(), [
                    'schedule_id' => $schedule->id,
                    'name' => $schedule->name
                ]);
            }
        }

        $this->info('Planlı yedeklemeler tamamlandı.');
    }
}
