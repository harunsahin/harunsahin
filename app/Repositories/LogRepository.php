<?php

namespace App\Repositories;

use App\Interfaces\LogRepositoryInterface;
use App\Models\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LogRepository implements LogRepositoryInterface
{
    protected $model;

    public function __construct(Log $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function create(array $data): Log
    {
        DB::beginTransaction();
        try {
            $log = $this->model->create($data);
            DB::commit();
            return $log;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Log
    {
        DB::beginTransaction();
        try {
            $log = $this->findById($id);
            $log->update($data);
            DB::commit();
            return $log;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->destroy($id);
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
            $result = $this->model->whereIn('id', $ids)->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?Log
    {
        return $this->model->with('user')->find($id);
    }

    public function getByLevel(string $level): LengthAwarePaginator
    {
        return $this->model->where('level', $level)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getByModule(string $module): LengthAwarePaginator
    {
        return $this->model->where('module', $module)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getLatest(int $limit = 10): array
    {
        return $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getErrors(): LengthAwarePaginator
    {
        return $this->model->where('level', 'error')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function clearLogs(): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->truncate();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function clearLogsBefore(string $date): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->where('created_at', '<', $date)->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 