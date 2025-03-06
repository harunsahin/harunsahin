<?php

namespace App\Interfaces;

use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionServiceInterface
{
    /**
     * Tüm izinleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni izin oluşturur
     */
    public function create(array $data): Permission;

    /**
     * İzin günceller
     */
    public function update(int $id, array $data): Permission;

    /**
     * İzin siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu izin siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre izin getirir
     */
    public function findById(int $id): ?Permission;

    /**
     * İzin durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Permission;

    /**
     * İzin rollerini günceller
     */
    public function updateRoles(int $id, array $roleIds): Permission;

    /**
     * İzin rollerini getirir
     */
    public function getRoles(int $id): array;

    /**
     * Aktif izinleri getirir
     */
    public function getActive(): array;

    /**
     * Modüle göre izinleri getirir
     */
    public function getByModule(string $module): array;
} 