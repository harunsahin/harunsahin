<?php

namespace App\Services;

use App\Interfaces\CompanyRepositoryInterface;
use App\Interfaces\CompanyServiceInterface;
use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CompanyService implements CompanyServiceInterface
{
    protected $repository;

    public function __construct(CompanyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Company
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Şirket oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Company
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Şirket güncelleme hatası:', [
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
            Log::error('Şirket silme hatası:', [
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
            Log::error('Toplu şirket silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Company
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(int $id, bool $isActive): Company
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('Şirket durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function addLogo(int $id, array $fileData): Company
    {
        try {
            return $this->repository->addLogo($id, $fileData);
        } catch (\Exception $e) {
            Log::error('Şirket logo ekleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function search(string $query): array
    {
        try {
            return $this->repository->search($query);
        } catch (\Exception $e) {
            Log::error('Şirket arama hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 