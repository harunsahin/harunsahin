<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Tüm kullanıcıları listeler
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Yeni kullanıcı oluşturur
     */
    public function create(array $data): User;

    /**
     * Kullanıcı günceller
     */
    public function update(int $id, array $data): User;

    /**
     * Kullanıcı siler
     */
    public function delete(int $id): bool;

    /**
     * Toplu kullanıcı siler
     */
    public function bulkDelete(array $ids): bool;

    /**
     * ID'ye göre kullanıcı getirir
     */
    public function findById(int $id): ?User;

    /**
     * Email'e göre kullanıcı getirir
     */
    public function findByEmail(string $email): ?User;

    /**
     * Kullanıcı durumunu günceller
     */
    public function updateStatus(int $id, bool $isActive): User;

    /**
     * Kullanıcı şifresini günceller
     */
    public function updatePassword(int $id, string $password): User;

    /**
     * Kullanıcı rolünü günceller
     */
    public function updateRole(int $id, int $roleId): User;

    /**
     * Kullanıcı arama yapar
     */
    public function search(string $query): array;
} 