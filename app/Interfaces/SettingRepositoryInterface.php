<?php

namespace App\Interfaces;

use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;

interface SettingRepositoryInterface
{
    /**
     * Tüm ayarları listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni ayar oluşturur
     */
    public function create(array $data): Setting;

    /**
     * Ayar günceller
     */
    public function update(int $id, array $data): Setting;

    /**
     * Ayar siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu ayar siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre ayar getirir
     */
    public function findById(int $id): ?Setting;

    /**
     * Anahtara göre ayar getirir
     */
    public function findByKey(string $key): ?Setting;

    /**
     * Ayar durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): Setting;

    /**
     * Modüle göre ayarları getirir
     */
    public function getByModule(string $module): array;

    /**
     * Tüm ayarları modül bazlı gruplar
     */
    public function getAllGroupedByModule(): array;

    /**
     * Ayarları toplu günceller
     */
    public function bulkUpdate(array $settings): bool;
} 