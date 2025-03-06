<?php

namespace App\Services;

use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\ActivityServiceInterface;
use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ActivityService implements ActivityServiceInterface
{
    /**
     * @var ActivityRepositoryInterface
     */
    protected $repository;

    /**
     * ActivityService constructor.
     *
     * @param ActivityRepositoryInterface $repository
     */
    public function __construct(ActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Tüm aktiviteleri getir
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        try {
            return $this->repository->getAll();
        } catch (\Exception $e) {
            Log::error('Aktiviteler getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Yeni aktivite oluştur
     *
     * @param array $data
     * @return Activity
     */
    public function create(array $data): Activity
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Aktivite oluşturulurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aktivite güncelle
     *
     * @param int $id
     * @param array $data
     * @return Activity
     */
    public function update(int $id, array $data): Activity
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Aktivite güncellenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aktivite sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Aktivite silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Toplu aktivite sil
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool
    {
        try {
            return $this->repository->bulkDelete($ids);
        } catch (\Exception $e) {
            Log::error('Aktiviteler toplu silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ID'ye göre aktivite getir
     *
     * @param int $id
     * @return Activity|null
     */
    public function findById(int $id): ?Activity
    {
        try {
            return $this->repository->findById($id);
        } catch (\Exception $e) {
            Log::error('Aktivite getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Kullanıcıya göre aktiviteleri getir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator
    {
        try {
            return $this->repository->getByUser($userId);
        } catch (\Exception $e) {
            Log::error('Kullanıcı aktiviteleri getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Modüle göre aktiviteleri getir
     *
     * @param string $module
     * @return LengthAwarePaginator
     */
    public function getByModule(string $module): LengthAwarePaginator
    {
        try {
            return $this->repository->getByModule($module);
        } catch (\Exception $e) {
            Log::error('Modül aktiviteleri getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tarih aralığına göre aktiviteleri getir
     *
     * @param string $startDate
     * @param string $endDate
     * @return LengthAwarePaginator
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        try {
            return $this->repository->getByDateRange($startDate, $endDate);
        } catch (\Exception $e) {
            Log::error('Tarih aralığı aktiviteleri getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Türüne göre aktiviteleri getir
     *
     * @param string $type
     * @return LengthAwarePaginator
     */
    public function getByType(string $type): LengthAwarePaginator
    {
        try {
            return $this->repository->getByType($type);
        } catch (\Exception $e) {
            Log::error('Tür aktiviteleri getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Son aktiviteleri getir
     *
     * @param int $limit
     * @return array
     */
    public function getLatest(int $limit = 10): array
    {
        try {
            return $this->repository->getLatest($limit);
        } catch (\Exception $e) {
            Log::error('Son aktiviteler getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Kullanıcının son aktivitelerini getir
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserLatest(int $userId, int $limit = 10): array
    {
        try {
            return $this->repository->getUserLatest($userId, $limit);
        } catch (\Exception $e) {
            Log::error('Kullanıcı son aktiviteleri getirilirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }
} 