<?php

namespace App\Repositories;

use App\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SettingRepository implements SettingRepositoryInterface
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->orderBy('module')->orderBy('key')->paginate(10);
    }

    public function create(array $data): Setting
    {
        DB::beginTransaction();
        try {
            $setting = $this->model->create($data);
            $this->clearCache();
            DB::commit();
            return $setting;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Setting
    {
        DB::beginTransaction();
        try {
            $setting = $this->findById($id);
            $setting->update($data);
            $this->clearCache();
            DB::commit();
            return $setting;
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
            $this->clearCache();
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
            $this->clearCache();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): ?Setting
    {
        return $this->model->find($id);
    }

    public function findByKey(string $key): ?Setting
    {
        return Cache::remember('setting.' . $key, 3600, function () use ($key) {
            return $this->model->where('key', $key)->first();
        });
    }

    public function updateStatus(int $id, bool $isActive): Setting
    {
        $setting = $this->findById($id);
        $setting->is_active = $isActive;
        $setting->save();
        $this->clearCache();
        return $setting;
    }

    public function getByModule(string $module): array
    {
        return Cache::remember('settings.module.' . $module, 3600, function () use ($module) {
            return $this->model->where('module', $module)
                ->where('is_active', true)
                ->orderBy('key')
                ->get()
                ->toArray();
        });
    }

    public function getAllGroupedByModule(): array
    {
        return Cache::remember('settings.all.grouped', 3600, function () {
            $settings = $this->model->where('is_active', true)
                ->orderBy('module')
                ->orderBy('key')
                ->get();

            $grouped = [];
            foreach ($settings as $setting) {
                $grouped[$setting->module][] = $setting;
            }

            return $grouped;
        });
    }

    public function bulkUpdate(array $settings): bool
    {
        DB::beginTransaction();
        try {
            foreach ($settings as $key => $value) {
                $this->model->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
            $this->clearCache();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function clearCache(): void
    {
        Cache::forget('settings.all.grouped');
        Cache::tags(['settings'])->flush();
    }
} 