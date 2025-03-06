<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status'));
        }

        $perPage = $request->input('per_page', 50);
        $companies = $query->latest()->paginate($perPage);

        if ($request->ajax()) {
            return view('companies.partials.table-rows', compact('companies'));
        }

        return view('companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'is_active' => 'boolean'
            ], [
                'name.required' => 'Firma adı zorunludur.',
                'name.max' => 'Firma adı en fazla 255 karakter olabilir.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.'
            ]);

            $validated['is_active'] = true; // Yeni eklenen firmalar varsayılan olarak aktif
            $validated['created_by'] = auth()->id();
            
            $company = Company::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Firma başarıyla eklendi.',
                    'data' => [
                        'id' => $company->id,
                        'name' => $company->name
                    ]
                ]);
            }

            return redirect()->route('companies.index')
                ->with('success', 'Firma başarıyla eklendi.');
        } catch (\Exception $e) {
            \Log::error('Company create error:', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Firma eklenirken bir hata oluştu: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Firma eklenirken bir hata oluştu.')
                ->withInput();
        }
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $company->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Şirket başarıyla güncellendi.'
        ]);
    }

    public function destroy(Company $company)
    {
        try {
            // İlişkili kayıtları kontrol etmeyi şimdilik kaldıralım
            // if ($company->offers()->exists()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Bu şirket tekliflerle ilişkili olduğu için silinemez.'
            //     ], 422);
            // }

            $company->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Şirket başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Şirket silme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Şirket silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Company $company)
    {
        $company->update(['is_active' => !$company->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Durum başarıyla güncellendi.'
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);

        $companies = Company::where('name', 'like', "%{$search}%")
            ->where('is_active', true)
            ->select('id', 'name')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'results' => $companies->items(),
            'pagination' => [
                'more' => $companies->hasMorePages()
            ]
        ]);
    }
} 