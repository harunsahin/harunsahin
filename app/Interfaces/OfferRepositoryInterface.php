<?php

namespace App\Interfaces;

use App\Models\Offer;
use Illuminate\Pagination\LengthAwarePaginator;

interface OfferRepositoryInterface
{
    /**
     * Tüm teklifleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni teklif oluşturur
     */
    public function create(array $data): Offer;

    /**
     * Teklif günceller
     */
    public function update(int $id, array $data): Offer;

    /**
     * Teklif siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu teklif siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre teklif getirir
     */
    public function findById(int $id): ?Offer;

    /**
     * Teklif durumunu günceller
     */
    public function updateStatus(int $id, int $statusId): Offer;

    /**
     * Teklif dosyası ekler
     */
    public function addFile(int $id, array $fileData): Offer;
} 