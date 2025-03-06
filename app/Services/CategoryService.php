<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CategoryService implements CategoryServiceInterface
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            return $this->repository->getAll();
        } catch (\Exception $e) {
            Log::error('Kategori listesi alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): Category
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Kategori oluşturulurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): Category
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Kategori güncellenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Kategori silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            return $this->repository->bulkDelete($ids);
        } catch (\Exception $e) {
            Log::error('Kategoriler toplu silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?Category
    {
        try {
            return $this->repository->findById($id);
        } catch (\Exception $e) {
            Log::error('Kategori bulunurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findBySlug(string $slug): ?Category
    {
        try {
            return $this->repository->findBySlug($slug);
        } catch (\Exception $e) {
            Log::error('Slug ile kategori bulunurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getParentCategories(): LengthAwarePaginator
    {
        try {
            return $this->repository->getParentCategories();
        } catch (\Exception $e) {
            Log::error('Üst kategoriler alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getChildCategories(int $parentId): LengthAwarePaginator
    {
        try {
            return $this->repository->getChildCategories($parentId);
        } catch (\Exception $e) {
            Log::error('Alt kategoriler alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllChildCategories(int $parentId): array
    {
        try {
            return $this->repository->getAllChildCategories($parentId);
        } catch (\Exception $e) {
            Log::error('Tüm alt kategoriler alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCategoryTree(): array
    {
        try {
            return $this->repository->getCategoryTree();
        } catch (\Exception $e) {
            Log::error('Kategori ağacı alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getActiveCategories(): LengthAwarePaginator
    {
        try {
            return $this->repository->getActiveCategories();
        } catch (\Exception $e) {
            Log::error('Aktif kategoriler alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getInactiveCategories(): LengthAwarePaginator
    {
        try {
            return $this->repository->getInactiveCategories();
        } catch (\Exception $e) {
            Log::error('Pasif kategoriler alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateStatus(int $id, bool $isActive): bool
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('Kategori durumu güncellenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOrder(int $id, int $order): bool
    {
        try {
            return $this->repository->updateOrder($id, $order);
        } catch (\Exception $e) {
            Log::error('Kategori sırası güncellenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }
} 