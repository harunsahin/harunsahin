<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PermissionRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('roles')->orderBy('name')->paginate(10);
    }

    public function create(array $data): Permission
    {
        DB::beginTransaction();
        try {
            $permission = $this->model->create($data);
            if (isset($data['roles'])) {
                $permission->roles()->sync($data['roles']);
            }
            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Permission
    {
        DB::beginTransaction();
        try {
            $permission = $this->findById($id);
            $permission->update($data);
            if (isset($data['roles'])) {
                $permission->roles()->sync($data['roles']);
            }
            DB::commit();
            return $permission;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $permission = $this->findById($id);
            $permission->roles()->detach();
            $result = $permission->delete();
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
            $permissions = $this->model->whereIn('id', $ids)->get();
            foreach ($permissions as $permission) {
                $permission->roles()->detach();
            }
            $result = $this->model->whereIn('id', $ids)->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?Permission
    {
        return $this->model->with('roles')->find($id);
    }

    public function updateStatus(int $id, bool $isActive): Permission
    {
        $permission = $this->findById($id);
        $permission->is_active = $isActive;
        $permission->save();
        return $permission;
    }

    public function updateRoles(int $id, array $roleIds): Permission
    {
        $permission = $this->findById($id);
        $permission->roles()->sync($roleIds);
        return $permission;
    }

    public function getRoles(int $id): array
    {
        $permission = $this->findById($id);
        return $permission->roles->pluck('id')->toArray();
    }

    public function getActive(): array
    {
        return $this->model->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getByModule(string $module): array
    {
        return $this->model->where('module', $module)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
    }
} 