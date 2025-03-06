<?php

namespace App\Services;

use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\RoleServiceInterface;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class RoleService implements RoleServiceInterface
{
    protected $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Role
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Rol oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Role
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Rol güncelleme hatası:', [
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
            Log::error('Rol silme hatası:', [
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
            Log::error('Toplu rol silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Role
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(int $id, bool $isActive): Role
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('Rol durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updatePermissions(int $id, array $permissionIds): Role
    {
        try {
            return $this->repository->updatePermissions($id, $permissionIds);
        } catch (\Exception $e) {
            Log::error('Rol izin güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getPermissions(int $id): array
    {
        try {
            return $this->repository->getPermissions($id);
        } catch (\Exception $e) {
            Log::error('Rol izin getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getActive(): array
    {
        try {
            return $this->repository->getActive();
        } catch (\Exception $e) {
            Log::error('Aktif rol getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getDefault(): ?Role
    {
        try {
            return $this->repository->getDefault();
        } catch (\Exception $e) {
            Log::error('Varsayılan rol getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 