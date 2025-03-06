<?php

namespace App\Repositories;

use App\Interfaces\StatusRepositoryInterface;
use App\Models\Status;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StatusRepository implements StatusRepositoryInterface
{
    protected $model;

    public function __construct(Status $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate(10);
    }

    public function create(array $data): Status
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Status
    {
        $status = $this->findById($id);
        $status->update($data);
        return $status;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?Status
    {
        return $this->model->find($id);
    }

    public function updateStatus(int $id, bool $isActive): Status
    {
        $status = $this->findById($id);
        $status->is_active = $isActive;
        $status->save();
        return $status;
    }

    public function getActive(): array
    {
        return $this->model->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getDefault(): ?Status
    {
        return $this->model->where('is_default', true)
            ->where('is_active', true)
            ->first();
    }
} 