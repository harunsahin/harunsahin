<?php

namespace App\Services;

use App\Interfaces\ResourceDefinitionRepositoryInterface;
use App\Interfaces\ResourceDefinitionServiceInterface;
use App\Models\KaynakTanim;
use Illuminate\Pagination\LengthAwarePaginator;

class ResourceDefinitionService implements ResourceDefinitionServiceInterface
{
    /**
     * @var ResourceDefinitionRepositoryInterface
     */
    protected $repository;

    /**
     * Constructor
     */
    public function __construct(ResourceDefinitionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Tüm kaynak tanımlarını listeler
     */
    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    /**
     * Yeni kaynak tanımı oluşturur
     */
    public function create(array $data): KaynakTanim
    {
        return $this->repository->create($data);
    }

    /**
     * Kaynak tanımını günceller
     */
    public function update(int $id, array $data): KaynakTanim
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Kaynak tanımını siler
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Toplu kaynak tanımı siler
     */
    public function bulkDelete(array $ids): bool
    {
        return $this->repository->bulkDelete($ids);
    }

    /**
     * Kaynak tanımlarını yeniden sıralar
     */
    public function reorder(array $order): bool
    {
        return $this->repository->reorder($order);
    }

    /**
     * ID'ye göre kaynak tanımı getirir
     */
    public function findById(int $id): ?KaynakTanim
    {
        return $this->repository->findById($id);
    }
} 