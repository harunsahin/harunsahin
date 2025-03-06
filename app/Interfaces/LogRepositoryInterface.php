<?php

namespace App\Interfaces;

use App\Models\Log;
use Illuminate\Pagination\LengthAwarePaginator;

interface LogRepositoryInterface
{
    /**
     * Tüm logları listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni log oluşturur
     */
    public function create(array $data): Log;

    /**
     * Log günceller
     */
    public function update(int $id, array $data): Log;

    /**
     * Log siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu log siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre log getirir
     */
    public function findById(int $id): ?Log;

    /**
     * Seviyeye göre logları getirir
     */
    public function getByLevel(string $level): LengthAwarePaginator;

    /**
     * Tarih aralığına göre logları getirir
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    /**
     * Kullanıcıya göre logları getirir
     */
    public function getByUser(int $userId): LengthAwarePaginator;

    /**
     * Modüle göre logları getirir
     */
    public function getByModule(string $module): LengthAwarePaginator;

    /**
     * Son logları getirir
     */
    public function getLatest(int $limit = 10): array;

    /**
     * Hata loglarını getirir
     */
    public function getErrors(): LengthAwarePaginator;

    /**
     * Logları temizler
     */
    public function clearLogs(): bool;

    /**
     * Belirli bir tarihten önceki logları temizler
     */
    public function clearLogsBefore(string $date): bool;
} 