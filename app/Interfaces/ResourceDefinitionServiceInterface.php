<?php

namespace App\Interfaces;

use App\Models\KaynakTanim;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResourceDefinitionServiceInterface
{
    /**
     * Tüm kaynak tanımlarını listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni kaynak tanımı oluşturur
     */
    public function create(array $data): KaynakTanim;

    /**
     * Kaynak tanımını günceller
     */
    public function update(int $id, array $data): KaynakTanim;

    /**
     * Kaynak tanımını siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu kaynak tanımı siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * Kaynak tanımlarını yeniden sıralar
     */
    public function reorder(array $order): bool;

    /**
     * ID'ye göre kaynak tanımı getirir
     */
    public function findById(int $id): ?KaynakTanim;
} 