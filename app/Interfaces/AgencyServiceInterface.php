<?php

namespace App\Interfaces;

use App\Models\Agency;
use Illuminate\Pagination\LengthAwarePaginator;

interface AgencyServiceInterface
{
    /**
     * Tüm acenteleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni acente oluşturur
     */
    public function create(array $data): Agency;

    /**
     * Acente günceller
     */
    public function update(int $id, array $data): Agency;

    /**
     * Acente siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu acente siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre acente getirir
     */
    public function findById(int $id): ?Agency;

    /**
     * Acente durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Agency;

    /**
     * Acente logo ekler
     */
    public function addLogo(int $id, array $fileData): Agency;
} 