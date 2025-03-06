<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use App\Http\Requests\BackupRequest;
use App\Jobs\CreateBackupJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        try {
            $backups = $this->backupService->getBackups();
            return view('settings.backups.index', compact('backups'));
        } catch (\Exception $e) {
            Log::error('Yedekleme listesi alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Yedekleme listesi alınırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $data = $this->backupService->getBackupData();
            return view('settings.backups.create', $data);
        } catch (\Exception $e) {
            Log::error('Yedekleme sayfası yüklenirken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Yedekleme sayfası yüklenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function store(BackupRequest $request)
    {
        try {
            // Önceki yedekleme işlemi devam ediyorsa kontrol et
            if (session('backup_in_progress', false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Başka bir yedekleme işlemi devam ediyor.'
                ], 409);
            }

            // Yedekleme işlemini başlat
            session(['backup_in_progress' => true]);
            session(['backup_status' => [
                'progress' => 0,
                'message' => 'Yedekleme başlatılıyor...',
                'can_cancel' => true
            ]]);

            // Yedekleme işlemini kuyruğa ekle
            CreateBackupJob::dispatch($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Yedekleme işlemi başlatıldı.',
                'data' => [
                    'status_url' => route('backups.progress')
                ]
            ]);
        } catch (\Exception $e) {
            // Hata durumunda session'ı temizle
            session()->forget('backup_status');
            session(['backup_in_progress' => false]);
            
            Log::error('Yedekleme oluşturulurken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Yedekleme oluşturulurken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($backup)
    {
        try {
            $response = $this->backupService->downloadBackup($backup);
            if ($response) {
                return $response;
            }
            return back()->with('error', 'Yedekleme dosyası bulunamadı.');
        } catch (\Exception $e) {
            Log::error('Yedekleme indirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Yedekleme indirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function destroy($backup)
    {
        try {
            if ($this->backupService->deleteBackup($backup)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Yedekleme başarıyla silindi.'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Yedekleme dosyası bulunamadı.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Yedekleme silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Yedekleme silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function progress()
    {
        return response()->json(session('backup_status', [
            'progress' => 0,
            'message' => 'Yedekleme başlatılmadı.',
            'can_cancel' => false
        ]));
    }

    public function cancel()
    {
        try {
            session()->forget('backup_status');
            session(['backup_in_progress' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Yedekleme işlemi iptal edildi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Yedekleme iptal hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Yedekleme işlemi iptal edilirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}