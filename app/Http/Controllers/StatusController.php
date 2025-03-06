<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('order')->get();
        return view('settings.statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'type' => 'required|string|in:general,offer,company,agency',
                'order' => 'required|integer|min:0'
            ]);

            // Slug oluştur
            $validated['slug'] = Str::slug($validated['name']);

            // Aktif olarak ayarla
            $validated['is_active'] = true;

            Status::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla oluşturuldu.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Durum oluşturulurken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Status $status)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'type' => 'required|string|in:general,offer,company,agency',
                'order' => 'required|integer|min:0'
            ]);

            // Slug güncelle
            $validated['slug'] = Str::slug($validated['name']);

            $status->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Durum güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Status $status)
    {
        try {
            // İlişkili kayıtları kontrol et
            if ($status->offers()->exists() || 
                $status->companies()->exists() || 
                $status->agencies()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu durum kullanımda olduğu için silinemez.'
                ], 422);
            }

            $status->delete();

            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Durum silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Status $status)
    {
        try {
            $status->update([
                'is_active' => !$status->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Durum başarıyla güncellendi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Durum güncellenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
} 