<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('permissions')->orderBy('name')->paginate(10);
    }

    public function create(array $data): Role
    {
        DB::beginTransaction();
        try {
            $role = $this->model->create($data);
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }
            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Role
    {
        DB::beginTransaction();
        try {
            $role = $this->findById($id);
            $role->update($data);
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }
            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $role = $this->findById($id);
            $role->permissions()->detach();
            $result = $role->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        DB::beginTransaction();
        try {
            $roles = $this->model->whereIn('id', $ids)->get();
            foreach ($roles as $role) {
                $role->permissions()->detach();
            }
            $result = $this->model->whereIn('id', $ids)->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?Role
    {
        return $this->model->with('permissions')->find($id);
    }

    public function updateStatus(int $id, bool $isActive): Role
    {
        $role = $this->findById($id);
        $role->is_active = $isActive;
        $role->save();
        return $role;
    }

    public function updatePermissions(int $id, array $permissionIds): Role
    {
        $role = $this->findById($id);
        $role->permissions()->sync($permissionIds);
        return $role;
    }

    public function getPermissions(int $id): array
    {
        $role = $this->findById($id);
        return $role->permissions->pluck('id')->toArray();
    }

    public function getActive(): array
    {
        return $this->model->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getDefault(): ?Role
    {
        return $this->model->where('is_default', true)->first();
    }
} 