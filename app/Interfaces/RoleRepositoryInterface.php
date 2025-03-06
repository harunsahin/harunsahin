<?php

namespace App\Interfaces;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    /**
     * Tüm rolleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni rol oluşturur
     */
    public function create(array $data): Role;

    /**
     * Rol günceller
     */
    public function update(int $id, array $data): Role;

    /**
     * Rol siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu rol siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre rol getirir
     */
    public function findById(int $id): ?Role;

    /**
     * Rol durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Role;

    /**
     * Rol izinlerini günceller
     */
    public function updatePermissions(int $id, array $permissionIds): Role;

    /**
     * Rol izinlerini getirir
     */
    public function getPermissions(int $id): array;

    /**
     * Aktif rolleri getirir
     */
    public function getActive(): array;

    /**
     * Varsayılan rolü getirir
     */
    public function getDefault(): ?Role;
} 