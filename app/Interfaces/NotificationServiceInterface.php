<?php

namespace App\Interfaces;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationServiceInterface
{
    /**
     * Tüm bildirimleri listeler
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni bir bildirim oluşturur
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification;

    /**
     * Bir bildirimi günceller
     *
     * @param int $id
     * @param array $data
     * @return Notification
     */
    public function update(int $id, array $data): Notification;

    /**
     * Bir bildirimi siler
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Birden fazla bildirimi siler
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre bildirim getirir
     *
     * @param int $id
     * @return Notification|null
     */
    public function findById(int $id): ?Notification;

    /**
     * Kullanıcıya göre bildirimleri getirir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Okunmamış bildirimleri getirir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getUnread(int $userId): LengthAwarePaginator;

    /**
     * Okunmuş bildirimleri getirir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getRead(int $userId): LengthAwarePaginator;

    /**
     * Tarih aralığına göre bildirimleri getirir
     *
     * @param string $startDate
     * @param string $endDate
     * @return LengthAwarePaginator
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Son bildirimleri getirir
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getLatest(int $userId, int $limit = 10): array;

    /**
     * Bildirimi okundu olarak işaretler
     *
     * @param int $id
     * @return bool
     */
    public function markAsRead(int $id): bool;

    /**
     * Tüm bildirimleri okundu olarak işaretler
     *
     * @param int $userId
     * @return bool
     */
    public function markAllAsRead(int $userId): bool;

    /**
     * Okunmamış bildirim sayısını getirir
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int;
} 