<?php

namespace App\Interfaces;

use App\Models\Status;
use Illuminate\Pagination\LengthAwarePaginator;

interface StatusRepositoryInterface
{
    /**
     * Tüm durumları listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni durum oluşturur
     */
    public function create(array $data): Status;

    /**
     * Durum günceller
     */
    public function update(int $id, array $data): Status;

    /**
     * Durum siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu durum siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre durum getirir
     */
    public function findById(int $id): ?Status;

    /**
     * Durum durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Status;

    /**
     * Aktif durumları getirir
     */
    public function getActive(): array;

    /**
     * Varsayılan durumu getirir
     */
    public function getDefault(): ?Status;
} 