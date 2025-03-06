<?php

namespace App\Services;

use App\Interfaces\AgencyRepositoryInterface;
use App\Interfaces\AgencyServiceInterface;
use App\Models\Agency;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class AgencyService implements AgencyServiceInterface
{
    protected $repository;

    public function __construct(AgencyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Agency
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Acente oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Agency
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Acente güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Acente silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            return $this->repository->bulkDelete($ids);
        } catch (\Exception $e) {
            Log::error('Toplu acente silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Agency
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(int $id, bool $isActive): Agency
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('Acente durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function addLogo(int $id, array $fileData): Agency
    {
        try {
            return $this->repository->addLogo($id, $fileData);
        } catch (\Exception $e) {
            Log::error('Acente logo ekleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getAgencies(array $filters = [])
    {
        $query = Agency::query();

        // Metin araması
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Durum filtresi
        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function createAgency(array $data)
    {
        try {
            Log::info('Agency store request:', $data);

            // İsim kontrolü
            if (Agency::where('name', $data['name'])->exists()) {
                throw new \Exception('Bu isimde bir acente zaten mevcut.');
            }

            // Varsayılan durum ID'sini kontrol et
            $defaultStatus = Status::where('name', 'Devam Ediyor')->first();
            if (!$defaultStatus) {
                throw new \Exception('Varsayılan durum bulunamadı.');
            }

            $data['is_active'] = $data['is_active'] ?? 1;
            $data['status_id'] = $defaultStatus->id;
            $data['created_by'] = auth()->id();

            DB::beginTransaction();
            
            $agency = Agency::create($data);
            
            DB::commit();
            
            Log::info('Agency created:', ['id' => $agency->id, 'name' => $agency->name]);

            return $agency;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateAgency(Agency $agency, array $data)
    {
        try {
            DB::beginTransaction();
            
            $agency->update($data);
            
            DB::commit();
            
            return $agency;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agency update error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteAgency(Agency $agency)
    {
        try {
            DB::beginTransaction();
            
            $agency->delete();
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agency delete error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function toggleStatus(Agency $agency, bool $status)
    {
        try {
            DB::beginTransaction();
            
            $agency->update(['is_active' => $status]);
            
            DB::commit();
            
            return $agency;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agency status toggle error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function searchAgencies(string $query)
    {
        try {
            Log::info('Acente arama isteği başladı:', ['query' => $query]);

            $searchQuery = Agency::query();

            if (!empty($query)) {
                $searchQuery->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                });
            }

            // Sadece aktif acenteleri getir
            $searchQuery->where('is_active', true);
            
            // İsme göre sırala
            $searchQuery->orderBy('name');

            $agencies = $searchQuery->get();

            Log::info('Bulunan acenteler:', [
                'count' => $agencies->count(),
                'agencies' => $agencies->toArray()
            ]);

            return $agencies;
        } catch (\Exception $e) {
            Log::error('Agency search error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 