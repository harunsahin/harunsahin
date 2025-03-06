<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with('parent')
            ->orderBy('order')
            ->paginate(10);
    }

    public function create(array $data): Category
    {
        DB::beginTransaction();
        try {
            if (!isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $category = $this->model->create($data);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): Category
    {
        DB::beginTransaction();
        try {
            $category = $this->findById($id);
            if (!isset($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            $category->update($data);
            DB::commit();
            return $category;
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

    public function findById(int $id): ?Category
    {
        return $this->model->with('parent')->find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getParentCategories(): LengthAwarePaginator
    {
        return $this->model->whereNull('parent_id')
            ->orderBy('order')
            ->paginate(10);
    }

    public function getChildCategories(int $parentId): LengthAwarePaginator
    {
        return $this->model->where('parent_id', $parentId)
            ->orderBy('order')
            ->paginate(10);
    }

    public function getAllChildCategories(int $parentId): array
    {
        $categories = $this->model->where('parent_id', $parentId)->get();
        $result = $categories->toArray();

        foreach ($categories as $category) {
            $result = array_merge($result, $this->getAllChildCategories($category->id));
        }

        return $result;
    }

    public function getCategoryTree(): array
    {
        $categories = $this->model->orderBy('order')->get();
        return $this->buildTree($categories);
    }

    protected function buildTree($categories, $parentId = null): array
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id);
                if ($children) {
                    $category->children = $children;
                }
                $branch[] = $category;
            }
        }

        return $branch;
    }

    public function getActiveCategories(): LengthAwarePaginator
    {
        return $this->model->where('is_active', true)
            ->orderBy('order')
            ->paginate(10);
    }

    public function getInactiveCategories(): LengthAwarePaginator
    {
        return $this->model->where('is_active', false)
            ->orderBy('order')
            ->paginate(10);
    }

    public function updateStatus(int $id, bool $isActive): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->where('id', $id)
                ->update(['is_active' => $isActive]);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateOrder(int $id, int $order): bool
    {
        DB::beginTransaction();
        try {
            $result = $this->model->where('id', $id)
                ->update(['order' => $order]);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 