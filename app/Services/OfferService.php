<?php

namespace App\Services;

use App\Interfaces\OfferRepositoryInterface;
use App\Interfaces\OfferServiceInterface;
use App\Models\Offer;
use App\Models\Status;
use App\Models\Company;
use App\Models\Agency;
use App\Models\OfferFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class OfferService implements OfferServiceInterface
{
    protected $repository;

    public function __construct(OfferRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Offer
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Offer
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->repository->bulkDelete($ids);
    }

    public function findById(int $id): ?Offer
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(int $id, int $statusId): Offer
    {
        return $this->repository->updateStatus($id, $statusId);
    }

    public function addFile(int $id, array $fileData): Offer
    {
        return $this->repository->addFile($id, $fileData);
    }

    public function getOffers(Request $request)
    {
        $query = Offer::with(['status', 'files', 'agency', 'company'])->latest();

        // Global arama
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('agency', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('company', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhere('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Tarih aralığı filtresi
        if ($request->date_start) {
            $query->where(function($q) use ($request) {
                $q->whereDate('checkin_date', '>=', $request->date_start)
                  ->orWhereDate('checkout_date', '>=', $request->date_start);
            });
        }
        if ($request->date_end) {
            $query->where(function($q) use ($request) {
                $q->whereDate('checkin_date', '<=', $request->date_end)
                  ->orWhereDate('checkout_date', '<=', $request->date_end);
            });
        }

        // Durum filtresi
        if ($request->status) {
            $query->where('status_id', $request->status);
        }

        $perPage = $request->input('per_page', 50);
        return $query->paginate($perPage);
    }

    public function createOffer(array $data, $files = null)
    {
        DB::beginTransaction();

        try {
            $data['created_by'] = auth()->id();
            $data['is_active'] = true;

            $offer = Offer::create($data);

            if ($files) {
                $this->handleFileUploads($offer, $files);
            }

            DB::commit();
            return $offer;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Teklif oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateOffer(Offer $offer, array $data, $files = null)
    {
        DB::beginTransaction();

        try {
            $offer->update($data);

            if ($files) {
                $this->handleFileUploads($offer, $files);
            }

            DB::commit();
            return $offer;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Teklif güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function handleFileUploads(Offer $offer, $files)
    {
        $processedFiles = [];

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            
            if (in_array($originalName, $processedFiles) || $file->getSize() === 0) {
                continue;
            }

            $fileName = $this->sanitizeFileName($file);
            $path = $file->storeAs('offer-files', $fileName, 'public');

            if (!$path) {
                throw new \Exception('Dosya yüklenemedi');
            }

            $offer->files()->create([
                'original_name' => $originalName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'created_by' => auth()->id()
            ]);

            $processedFiles[] = $originalName;
        }
    }

    private function sanitizeFileName($file)
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        $fileName = str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç', ' '],
            ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c', '_'],
            $fileName
        );
        
        $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower($fileName));
        
        return $fileName . '_' . time() . '.' . $extension;
    }

    public function getFormData()
    {
        return [
            'statuses' => Status::where('is_active', true)->get(),
            'companies' => Company::where('is_active', true)->orderBy('name')->get(),
            'agencies' => Agency::orderBy('name')->get()
        ];
    }
} 