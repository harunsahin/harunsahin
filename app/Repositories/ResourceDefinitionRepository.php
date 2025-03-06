<?php

namespace App\Repositories;

use App\Interfaces\ResourceDefinitionRepositoryInterface;
use App\Models\KaynakTanim;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ResourceDefinitionRepository implements ResourceDefinitionRepositoryInterface
{
    protected $model;

    public function __construct(KaynakTanim $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->orderBy('position')->paginate(10);
    }

    public function create(array $data): KaynakTanim
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): KaynakTanim
    {
        $resource = $this->findById($id);
        $resource->update($data);
        return $resource;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function reorder(array $order): bool
    {
        try {
            DB::beginTransaction();

            foreach ($order as $position => $id) {
                $this->model->where('id', $id)->update(['position' => $position + 1]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function findById(int $id): ?KaynakTanim
    {
        return $this->model->find($id);
    }
} 