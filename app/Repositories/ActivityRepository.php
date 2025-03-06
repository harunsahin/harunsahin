<?php

namespace App\Repositories;

use App\Interfaces\ActivityRepositoryInterface;
use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ActivityRepository implements ActivityRepositoryInterface
{
    /**
     * @var Activity
     */
    protected $model;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     */
    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * Tüm aktiviteleri getir
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return $this->model->latest()->paginate(10);
    }

    /**
     * Yeni aktivite oluştur
     *
     * @param array $data
     * @return Activity
     */
    public function create(array $data): Activity
    {
        DB::beginTransaction();
        try {
            $activity = $this->model->create($data);
            DB::commit();
            return $activity;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Aktivite güncelle
     *
     * @param int $id
     * @param array $data
     * @return Activity
     */
    public function update(int $id, array $data): Activity
    {
        DB::beginTransaction();
        try {
            $activity = $this->findById($id);
            $activity->update($data);
            DB::commit();
            return $activity;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Aktivite sil
     *
     * @param int $id
     * @return bool
     */
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

    /**
     * Toplu aktivite sil
     *
     * @param array $ids
     * @return bool
     */
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

    /**
     * ID'ye göre aktivite getir
     *
     * @param int $id
     * @return Activity|null
     */
    public function findById(int $id): ?Activity
    {
        return $this->model->find($id);
    }

    /**
     * Kullanıcıya göre aktiviteleri getir
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate(10);
    }

    /**
     * Modüle göre aktiviteleri getir
     *
     * @param string $module
     * @return LengthAwarePaginator
     */
    public function getByModule(string $module): LengthAwarePaginator
    {
        return $this->model->where('module', $module)->latest()->paginate(10);
    }

    /**
     * Tarih aralığına göre aktiviteleri getir
     *
     * @param string $startDate
     * @param string $endDate
     * @return LengthAwarePaginator
     */
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])->latest()->paginate(10);
    }

    /**
     * Türüne göre aktiviteleri getir
     *
     * @param string $type
     * @return LengthAwarePaginator
     */
    public function getByType(string $type): LengthAwarePaginator
    {
        return $this->model->where('type', $type)->latest()->paginate(10);
    }

    /**
     * Son aktiviteleri getir
     *
     * @param int $limit
     * @return array
     */
    public function getLatest(int $limit = 10): array
    {
        return $this->model->latest()->take($limit)->get()->toArray();
    }

    /**
     * Kullanıcının son aktivitelerini getir
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserLatest(int $userId, int $limit = 10): array
    {
        return $this->model->where('user_id', $userId)->latest()->take($limit)->get()->toArray();
    }
} 