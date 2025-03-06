<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Status;
use App\Models\Company;
use App\Models\Agency;
use App\Models\OfferFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function getDashboardData()
    {
        try {
            // Toplam teklif sayısı
            $totalOffers = Offer::count();
            
            // Son 30 gündeki teklifler
            $lastMonthOffers = Offer::where('created_at', '>=', now()->subDays(30))->count();
            
            // Durum istatistikleri
            $statusStats = $this->getStatusStats();

            // En çok teklif veren 3 acente
            $topAgencies = $this->getTopAgencies();

            // En çok teklif veren 3 firma
            $topCompanies = $this->getTopCompanies();

            // Devam eden teklifler
            $pendingOffersList = $this->getPendingOffers();

            // Onaylı teklifler
            $approvedOffersList = $this->getApprovedOffers();

            // Formlar için gerekli veriler
            $statuses = Status::where('is_active', true)->get();
            $companies = Company::where('is_active', true)->get();
            $agencies = Agency::where('is_active', true)->get();

            return compact(
                'totalOffers',
                'lastMonthOffers',
                'statusStats',
                'pendingOffersList',
                'approvedOffersList',
                'statuses',
                'companies',
                'agencies',
                'topAgencies',
                'topCompanies'
            );
        } catch (\Exception $e) {
            Log::error('Dashboard verisi alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getStats()
    {
        try {
            $totalOffers = Offer::count();
            $monthlyStats = Offer::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $monthlyLabels = $monthlyStats->pluck('month')->map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
            });

            $monthlyData = $monthlyStats->pluck('total');

            return [
                'totalOffers' => $totalOffers,
                'monthlyLabels' => $monthlyLabels,
                'monthlyData' => $monthlyData
            ];
        } catch (\Exception $e) {
            Log::error('İstatistikler alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getOfferDetails(Offer $offer)
    {
        try {
            $offer->load(['status', 'files', 'user:id,name', 'agency', 'company']);
            
            return [
                'id' => $offer->id,
                'agency' => optional($offer->agency)->name ?? $offer->agency,
                'company' => optional($offer->company)->name ?? $offer->company,
                'full_name' => $offer->full_name,
                'phone' => $offer->phone,
                'email' => $offer->email,
                'room_count' => $offer->room_count,
                'pax_count' => $offer->pax_count,
                'checkin_date' => $offer->checkin_date,
                'checkout_date' => $offer->checkout_date,
                'option_date' => $offer->option_date,
                'notes' => $offer->notes,
                'status' => [
                    'name' => $offer->status->name,
                    'color' => $offer->status->color
                ],
                'files' => $offer->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'original_name' => $file->original_name,
                        'file_path' => $file->file_path,
                        'mime_type' => $file->mime_type,
                        'file_size' => $file->file_size,
                        'url' => route('dashboard.files.download', $file->id)
                    ];
                }),
                'creator' => $offer->user ? [
                    'name' => $offer->user->name
                ] : null,
                'created_at' => $offer->created_at
            ];
        } catch (\Exception $e) {
            Log::error('Teklif detayları alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function downloadFile(OfferFile $file)
    {
        try {
            if (!Storage::disk('public')->exists($file->file_path)) {
                throw new \Exception('Dosya bulunamadı.');
            }

            return Storage::disk('public')->download(
                $file->file_path,
                $file->original_name,
                ['Content-Type' => $file->mime_type]
            );
        } catch (\Exception $e) {
            Log::error('Dosya indirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getStatusStats()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Durum istatistikleri alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getTopAgencies()
    {
        return Offer::select('agency_id', DB::raw('count(*) as total'))
            ->whereNotNull('agency_id')
            ->with('agency:id,name')
            ->groupBy('agency_id')
            ->orderByDesc('total')
            ->limit(3)
            ->get();
    }

    private function getTopCompanies()
    {
        return Offer::select('company_id', DB::raw('count(*) as total'))
            ->whereNotNull('company_id')
            ->with('company:id,name')
            ->groupBy('company_id')
            ->orderByDesc('total')
            ->limit(3)
            ->get();
    }

    private function getPendingOffers()
    {
        return Offer::with(['status', 'agency', 'company'])
            ->whereHas('status', function($query) {
                $query->where('name', 'Devam Ediyor');
            })
            ->latest()
            ->take(5)
            ->get();
    }

    private function getApprovedOffers()
    {
        return Offer::with(['status', 'agency', 'company'])
            ->whereHas('status', function($query) {
                $query->where('name', 'Onaylandı');
            })
            ->latest()
            ->take(5)
            ->get();
    }
} 