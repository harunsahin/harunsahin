<?php

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function create(array $data): Notification
    {
        DB::beginTransaction();
        try {
            $notification = $this->model->create($data);
            DB::commit();
            return $notification;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Notification
    {
        DB::beginTransaction();
        try {
            $notification = $this->findById($id);
            $notification->update($data);
            DB::commit();
            return $notification;
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

    public function findById(int $id): ?Notification
    {
        return $this->model->with('user')->find($id);
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getUnread(int $userId): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getRead(int $userId): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getLatest(int $userId, int $limit = 10): array
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function markAsRead(int $id): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->where('id', $id)
                ->update(['read_at' => now()]);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function markAllAsRead(int $userId): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->where('user_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->model->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
} 