<?php

namespace App\Interfaces;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentServiceInterface
{
    /**
     * Tüm yorumları getir
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni yorum oluştur
     *
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment;

    /**
     * Yorum güncelle
     *
     * @param int $id
     * @param array $data
     * @return Comment
     */
    public function update(int $id, array $data): Comment;

    /**
     * Yorum sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Toplu yorum sil
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool;

    /**
     * Yorum durumunu güncelle
     *
     * @param int $id
     * @param bool $status
     * @return Comment
     */
    public function updateStatus(int $id, bool $status): Comment;

    /**
     * Tarih aralığına göre yorumları getir
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Son yorumları getir
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection;

    /**
     * ID'ye göre yorum getirir
     *
     * @param int $id
     * @return Comment|null
     */
    public function findById(int $id): ?Comment;

    /**
     * Kullanıcıya göre yorumları getirir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Onaylanmış yorumları getirir
     *
     * @return LengthAwarePaginator
     */
    public function getApproved(): LengthAwarePaginator;

    /**
     * Onaylanmamış yorumları getirir
     *
     * @return LengthAwarePaginator
     */
    public function getPending(): LengthAwarePaginator;

    /**
     * Reddedilmiş yorumları getirir
     *
     * @return LengthAwarePaginator
     */
    public function getRejected(): LengthAwarePaginator;

    /**
     * Yorum sayısını getirir
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Kullanıcının yorum sayısını getirir
     *
     * @param int $userId
     * @return int
     */
    public function getUserCommentCount(int $userId): int;
} 