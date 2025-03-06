<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * Tüm kategorileri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni kategori oluşturur
     */
    public function create(array $data): Category;

    /**
     * Kategori günceller
     */
    public function update(int $id, array $data): Category;

    /**
     * Kategori siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu kategori siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre kategori getirir
     */
    public function findById(int $id): ?Category;

    /**
     * Slug'a göre kategori getirir
     */
    public function findBySlug(string $slug): ?Category;

    /**
     * Üst kategorileri getirir
     */
    public function getParentCategories(): LengthAwarePaginator;

    /**
     * Alt kategorileri getirir
     */
    public function getChildCategories(int $parentId): LengthAwarePaginator;

    /**
     * Tüm alt kategorileri getirir (recursive)
     */
    public function getAllChildCategories(int $parentId): array;

    /**
     * Kategori ağacını getirir
     */
    public function getCategoryTree(): array;

    /**
     * Aktif kategorileri getirir
     */
    public function getActiveCategories(): LengthAwarePaginator;

    /**
     * Pasif kategorileri getirir
     */
    public function getInactiveCategories(): LengthAwarePaginator;

    /**
     * Kategori durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): bool;

    /**
     * Kategori sırasını günceller
     */
    public function updateOrder(int $id, int $order): bool;
} 