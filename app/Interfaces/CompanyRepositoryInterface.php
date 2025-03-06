<?php

namespace App\Interfaces;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompanyRepositoryInterface
{
    /**
     * Tüm şirketleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni şirket oluşturur
     */
    public function create(array $data): Company;

    /**
     * Şirket günceller
     */
    public function update(int $id, array $data): Company;

    /**
     * Şirket siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu şirket siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre şirket getirir
     */
    public function findById(int $id): ?Company;

    /**
     * Şirket durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Company;

    /**
     * Şirket logo ekler
     */
    public function addLogo(int $id, array $fileData): Company;

    /**
     * Şirket arama yapar
     */
    public function search(string $query): array;
} 