<?php

namespace App\Interfaces;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    /**
     * Tüm yorumları listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni yorum oluşturur
     */
    public function create(array $data): Comment;

    /**
     * Yorum günceller
     */
    public function update(int $id, array $data): Comment;

    /**
     * Yorum siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu yorum siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre yorum getirir
     */
    public function findById(int $id): ?Comment;

    /**
     * Kullanıcıya göre yorumları getirir
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Onaylanmış yorumları getirir
     */
    public function getApproved(): LengthAwarePaginator;

    /**
     * Onaylanmamış yorumları getirir
     */
    public function getPending(): LengthAwarePaginator;

    /**
     * Reddedilmiş yorumları getirir
     */
    public function getRejected(): LengthAwarePaginator;

    /**
     * Yorum durumunu günceller
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Tarih aralığına göre yorumları getirir
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Son yorumları getirir
     */
    public function getLatest(int $limit = 10): array;

    /**
     * Yorum sayısını getirir
     */
    public function getCount(): int;

    /**
     * Kullanıcının yorum sayısını getirir
     */
    public function getUserCommentCount(int $userId): int;
} 