<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Status;
use App\Models\Company;
use App\Models\Agency;
use App\Models\OfferFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        try {
            $data = $this->dashboardService->getDashboardData();
            return view('dashboard', $data);
        } catch (\Exception $e) {
            Log::error('Dashboard yüklenirken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Dashboard yüklenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            $stats = $this->dashboardService->getStats();
            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('İstatistikler alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'İstatistikler alınırken bir hata oluştu.'
            ], 500);
        }
    }

    public function getOffer(Offer $offer)
    {
        try {
            $offerDetails = $this->dashboardService->getOfferDetails($offer);
            return response()->json($offerDetails);
        } catch (\Exception $e) {
            Log::error('Teklif detayları alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Teklif detayları alınırken bir hata oluştu.'
            ], 500);
        }
    }

    public function downloadFile(OfferFile $file)
    {
        try {
            return $this->dashboardService->downloadFile($file);
        } catch (\Exception $e) {
            Log::error('Dosya indirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Dosya indirilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    private function getStatusStats()
    {
        $totalOffers = Offer::count();
        $currentMonth = now()->startOfMonth();
        
        $stats = [];
        
        // Ana durumlar için istatistikler
        $mainStatuses = Status::whereIn('name', [
            'Devam Ediyor',
            'Onaylandı',
            'İptal Edildi',
            'Tamamlandı',
            'Beklemede'
        ])->get();

        foreach ($mainStatuses as $status) {
            $count = Offer::where('status_id', $status->id)->count();
            $monthlyCount = Offer::where('status_id', $status->id)
                ->whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentMonth->year)
                ->count();

            if ($count > 0 || $monthlyCount > 0) {
                $stats[$status->id] = [
                    'name' => $status->name,
                    'count' => $count,
                    'monthlyCount' => $monthlyCount,
                    'rate' => $totalOffers > 0 ? ($count / $totalOffers) * 100 : 0,
                    'color' => $status->color
                ];
            }
        }

        // Diğer durumlar için istatistikler
        $otherStatusesCount = Offer::whereNotIn('status_id', $mainStatuses->pluck('id'))->count();
        $otherStatusesMonthlyCount = Offer::whereNotIn('status_id', $mainStatuses->pluck('id'))
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();

        if ($otherStatusesCount > 0 || $otherStatusesMonthlyCount > 0) {
            $stats['others'] = [
                'name' => 'Diğer Durumlar',
                'count' => $otherStatusesCount,
                'monthlyCount' => $otherStatusesMonthlyCount,
                'rate' => $totalOffers > 0 ? ($otherStatusesCount / $totalOffers) * 100 : 0,
                'color' => '#6c757d'
            ];
        }

        return $stats;
    }
} 