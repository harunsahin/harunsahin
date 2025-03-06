<?php

namespace App\Interfaces;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    /**
     * Tüm bildirimleri listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni bildirim oluşturur
     */
    public function create(array $data): Notification;

    /**
     * Bildirim günceller
     */
    public function update(int $id, array $data): Notification;

    /**
     * Bildirim siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu bildirim siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre bildirim getirir
     */
    public function findById(int $id): ?Notification;

    /**
     * Kullanıcıya göre bildirimleri getirir
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Okunmamış bildirimleri getirir
     */
    public function getUnread(int $userId): LengthAwarePaginator;

    /**
     * Okunmuş bildirimleri getirir
     */
    public function getRead(int $userId): LengthAwarePaginator;

    /**
     * Tarih aralığına göre bildirimleri getirir
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Son bildirimleri getirir
     */
    public function getLatest(int $userId, int $limit = 10): array;

    /**
     * Bildirimi okundu olarak işaretler
     */
    public function markAsRead(int $id): bool;

    /**
     * Tüm bildirimleri okundu olarak işaretler
     */
    public function markAllAsRead(int $userId): bool;

    /**
     * Bildirim sayısını getirir
     */
    public function getUnreadCount(int $userId): int;
} 