<?php

namespace App\Interfaces;

use App\Models\Log;
use Illuminate\Pagination\LengthAwarePaginator;

interface LogServiceInterface
{
    /**
     * Tüm logları listeler
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni bir log oluşturur
     *
     * @param array $data
     * @return Log
     */
    public function create(array $data): Log;

    /**
     * Bir logu günceller
     *
     * @param int $id
     * @param array $data
     * @return Log
     */
    public function update(int $id, array $data): Log;

    /**
     * Bir logu siler
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Birden fazla logu siler
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre log getirir
     *
     * @param int $id
     * @return Log|null
     */
    public function findById(int $id): ?Log;

    /**
     * Seviyeye göre logları getirir
     *
     * @param string $level
     * @return LengthAwarePaginator
     */
    public function getByLevel(string $level): LengthAwarePaginator;

    /**
     * Tarih aralığına göre logları getirir
     *
     * @param string $startDate
     * @param string $endDate
     * @return LengthAwarePaginator
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Kullanıcıya göre logları getirir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Modüle göre logları getirir
     *
     * @param string $module
     * @return LengthAwarePaginator
     */
    public function getByModule(string $module): LengthAwarePaginator;

    /**
     * En son logları getirir
     *
     * @param int $limit
     * @return array
     */
    public function getLatest(int $limit = 10): array;

    /**
     * Hata loglarını getirir
     *
     * @return LengthAwarePaginator
     */
    public function getErrors(): LengthAwarePaginator;

    /**
     * Tüm logları temizler
     *
     * @return bool
     */
    public function clearLogs(): bool;

    /**
     * Belirli bir tarihten önceki logları temizler
     *
     * @param string $date
     * @return bool
     */
    public function clearLogsBefore(string $date): bool;

    /**
     * Hata logu oluşturur
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string $message, array $context = []): void;
} 