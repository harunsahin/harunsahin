<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Services\AgencyService;
use App\Http\Requests\AgencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgencyController extends Controller
{
    protected $agencyService;

    public function __construct(AgencyService $agencyService)
    {
        $this->agencyService = $agencyService;
    }

    public function index(Request $request)
    {
        try {
            $agencies = $this->agencyService->getAgencies([
                'search' => $request->search,
                'status' => $request->status
            ]);

            if ($request->ajax()) {
                return view('agencies.partials.table', compact('agencies'))->render();
            }

            return view('agencies.index', compact('agencies'));
        } catch (\Exception $e) {
            Log::error('Acente listesi alınırken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Acente listesi alınırken bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function store(AgencyRequest $request)
    {
        try {
            $agency = $this->agencyService->createAgency($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Acenta başarıyla eklendi.',
                'data' => [
                    'id' => $agency->id,
                    'name' => $agency->name
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Acente eklenirken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acenta eklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(AgencyRequest $request, Agency $agency)
    {
        try {
            $this->agencyService->updateAgency($agency, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Acente başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Acente güncellenirken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acente güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Agency $agency)
    {
        try {
            $this->agencyService->deleteAgency($agency);
            
            return response()->json([
                'success' => true,
                'message' => 'Acente başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Acente silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acente silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Agency $agency)
    {
        try {
            return response()->json([
                'success' => true,
                'agency' => $agency
            ]);
        } catch (\Exception $e) {
            Log::error('Acente detayları getirilemedi:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acente detayları getirilemedi.'
            ], 500);
        }
    }

    public function toggleStatus(Request $request, Agency $agency)
    {
        try {
            $this->agencyService->toggleStatus($agency, $request->input('status', !$agency->is_active));
            
            return response()->json([
                'success' => true,
                'message' => 'Acente durumu başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            Log::error('Acente durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acente durumu güncellenirken bir hata oluştu.'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $agencies = $this->agencyService->searchAgencies($request->q ?? '');
            
            return response()->json([
                'success' => true,
                'data' => $agencies
            ]);
        } catch (\Exception $e) {
            Log::error('Acente arama hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Acente araması yapılırken bir hata oluştu.'
            ], 500);
        }
    }
} 