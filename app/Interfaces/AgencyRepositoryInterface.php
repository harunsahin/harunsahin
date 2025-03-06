<?php

namespace App\Interfaces;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AgencyRepositoryInterface
{
    /**
     * Tüm acenteleri getir
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni acente oluştur
     *
     * @param array $data
     * @return Agency
     */
    public function create(array $data): Agency;

    /**
     * Acente güncelle
     *
     * @param int $id
     * @param array $data
     * @return Agency
     */
    public function update(int $id, array $data): Agency;

    /**
     * Acente sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Toplu acente sil
     *
     * @param array $ids
     * @return bool
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre acente getir
     *
     * @param int $id
     * @return Agency|null
     */
    public function findById(int $id): ?Agency;

    /**
     * Acente adına göre ara
     *
     * @param string $name
     * @return Collection
     */
    public function searchByName(string $name): Collection;

    /**
     * Acente sayısını getir
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Son eklenen acenteleri getir
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection;
} 