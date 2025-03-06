<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Status;
use App\Models\Company;
use App\Models\Agency;
use App\Models\OfferFile;
use App\Services\OfferService;
use App\Http\Requests\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index(Request $request)
    {
        try {
            $offers = $this->offerService->getOffers($request);

            if ($request->ajax()) {
                return view('offers.partials.table-rows', compact('offers'));
            }

            $formData = $this->offerService->getFormData();
            return view('offers.index', array_merge(compact('offers'), $formData));
        } catch (\Exception $e) {
            Log::error('Index error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veriler yüklenirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Veriler yüklenirken bir hata oluştu.');
        }
    }

    public function create()
    {
        return view('offers.create', $this->offerService->getFormData());
    }

    public function store(OfferRequest $request)
    {
        try {
            $offer = $this->offerService->createOffer(
                $request->validated(),
                $request->file('files')
            );

            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla oluşturuldu.'
            ]);
        } catch (\Exception $e) {
            Log::error('Teklif oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Teklif oluşturulurken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Offer $offer)
    {
        return view('offers.edit', array_merge(
            compact('offer'),
            $this->offerService->getFormData()
        ));
    }

    public function update(OfferRequest $request, Offer $offer)
    {
        try {
            $this->offerService->updateOffer(
                $offer,
                $request->validated(),
                $request->file('files')
            );

            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Teklif güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Teklif güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Offer $offer)
    {
        try {
            $offer->delete();
            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Teklif silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Teklif silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:offers,id'
            ]);

            Offer::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' adet teklif başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Teklifler silinirken bir hata oluştu.'
            ], 500);
        }
    }

    // Dosya silme metodu
    public function deleteFile($fileId)
    {
        try {
            $file = OfferFile::findOrFail($fileId);
            
            // Dosyayı diskten sil
            Storage::disk('public')->delete($file->file_path);
            
            // Veritabanından sil
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dosya başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dosya silinirken bir hata oluştu.'
            ], 500);
        }
    }

    // Teklif detaylarını getir
    public function show(Offer $offer)
    {
        try {
            // Debug için teklif bilgilerini logla
            \Log::info('Teklif detayları yükleniyor:', [
                'offer_id' => $offer->id,
                'has_agency' => $offer->agency_id ? true : false,
                'has_company' => $offer->company_id ? true : false,
                'has_status' => $offer->status_id ? true : false,
                'raw_data' => $offer->toArray()
            ]);

            // İlişkili modelleri yükle
            $offer->load(['agency', 'company', 'status', 'files']);
            
            // İlişkili modelleri kontrol et ve logla
            \Log::info('İlişkili modeller yüklendi:', [
                'agency' => $offer->agency ? $offer->agency->toArray() : null,
                'company' => $offer->company ? $offer->company->toArray() : null,
                'status' => $offer->status ? $offer->status->toArray() : null,
                'files_count' => $offer->files->count()
            ]);

            // Status bilgisini güvenli bir şekilde al
            $statusData = null;
            if ($offer->status) {
                $statusData = [
                    'id' => $offer->status->id,
                    'name' => $offer->status->name,
                    'color' => $offer->status->color
                ];
            }

            // Verileri hazırla
            $data = [
                'id' => $offer->id,
                'agency' => $offer->agency ? $offer->agency->name : '-',
                'company' => $offer->company ? $offer->company->name : '-',
                'full_name' => $offer->full_name ?? '-',
                'phone' => $offer->phone ?? '-',
                'email' => $offer->email ?? '-',
                'room_pax' => $offer->room_count && $offer->pax_count ? 
                    "{$offer->room_count} Oda / {$offer->pax_count} Kişi" : '-',
                'checkin_date' => $offer->checkin_date ? date('d.m.Y', strtotime($offer->checkin_date)) : '-',
                'checkout_date' => $offer->checkout_date ? date('d.m.Y', strtotime($offer->checkout_date)) : '-',
                'option_date' => $offer->option_date ? date('d.m.Y', strtotime($offer->option_date)) : '-',
                'status' => $statusData,
                'notes' => $offer->notes ?? '-',
                'files' => $offer->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->original_name,
                        'url' => Storage::disk('public')->url($file->file_path)
                    ];
                })->toArray()
            ];

            // Oluşturulan veriyi logla
            \Log::info('Teklif detayları hazırlandı:', ['data' => $data]);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Teklif detayları yüklenirken hata oluştu:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'offer_id' => $offer->id,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Teklif detayları yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadFile($fileId)
    {
        try {
            $file = OfferFile::findOrFail($fileId);
            
            // Dosyanın fiziksel olarak var olup olmadığını kontrol et
            if (!Storage::disk('public')->exists($file->file_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosya bulunamadı.'
                ], 404);
            }

            // Dosyayı indir
            return Storage::disk('public')->download(
                $file->file_path,
                $file->original_name,
                ['Content-Type' => $file->mime_type]
            );

        } catch (\Exception $e) {
            \Log::error('Dosya indirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dosya indirilirken bir hata oluştu.'
            ], 500);
        }
    }
}
