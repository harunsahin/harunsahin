<?php

namespace App\Interfaces;

use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

interface ActivityServiceInterface
{
    /**
     * Tüm aktiviteleri getir
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni aktivite oluştur
     *
     * @param array $data
     * @return Activity
     */
    public function create(array $data): Activity;

    /**
     * Aktivite güncelle
     *
     * @param int $id
     * @param array $data
     * @return Activity
     */
    public function update(int $id, array $data): Activity;

    /**
     * Aktivite sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Toplu aktivite sil
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre aktivite getir
     *
     * @param int $id
     * @return Activity|null
     */
    public function findById(int $id): ?Activity;

    /**
     * Kullanıcıya göre aktiviteleri getir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Modüle göre aktiviteleri getir
     *
     * @param string $module
     * @return LengthAwarePaginator
     */
    public function getByModule(string $module): LengthAwarePaginator;

    /**
     * Tarih aralığına göre aktiviteleri getir
     *
     * @param string $startDate
     * @param string $endDate
     * @return LengthAwarePaginator
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Türüne göre aktiviteleri getir
     *
     * @param string $type
     * @return LengthAwarePaginator
     */
    public function getByType(string $type): LengthAwarePaginator;

    /**
     * Son aktiviteleri getir
     *
     * @param int $limit
     * @return array
     */
    public function getLatest(int $limit = 10): array;

    /**
     * Kullanıcının son aktivitelerini getir
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserLatest(int $userId, int $limit = 10): array;
} 