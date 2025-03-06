<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('role')->orderBy('name')->paginate(10);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?User
    {
        return $this->model->with('role')->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function updateStatus(int $id, bool $isActive): User
    {
        $user = $this->findById($id);
        $user->is_active = $isActive;
        $user->save();
        return $user;
    }

    public function updatePassword(int $id, string $password): User
    {
        $user = $this->findById($id);
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function updateRole(int $id, int $roleId): User
    {
        $user = $this->findById($id);
        $user->role_id = $roleId;
        $user->save();
        return $user;
    }

    public function search(string $query): array
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->toArray();
    }
} 