<?php

namespace App\Services;

use App\Interfaces\SettingRepositoryInterface;
use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class SettingService implements SettingServiceInterface
{
    protected $repository;

    public function __construct(SettingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Setting
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Ayar oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Setting
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Ayar güncelleme hatası:', [
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
            Log::error('Ayar silme hatası:', [
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
            Log::error('Toplu ayar silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Setting
    {
        return $this->repository->findById($id);
    }

    public function findByKey(string $key): ?Setting
    {
        return $this->repository->findByKey($key);
    }

    public function updateStatus(int $id, bool $isActive): Setting
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('Ayar durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getByModule(string $module): array
    {
        try {
            return $this->repository->getByModule($module);
        } catch (\Exception $e) {
            Log::error('Modül ayarları getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getAllGroupedByModule(): array
    {
        try {
            return $this->repository->getAllGroupedByModule();
        } catch (\Exception $e) {
            Log::error('Gruplandırılmış ayarlar getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function bulkUpdate(array $settings): bool
    {
        try {
            return $this->repository->bulkUpdate($settings);
        } catch (\Exception $e) {
            Log::error('Toplu ayar güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 