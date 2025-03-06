<?php

namespace App\Repositories;

use App\Interfaces\AgencyRepositoryInterface;
use App\Models\Agency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgencyRepository implements AgencyRepositoryInterface
{
    protected $model;

    public function __construct(Agency $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate(10);
    }

    public function create(array $data): Agency
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Agency
    {
        $agency = $this->findById($id);
        $agency->update($data);
        return $agency;
    }

    public function delete(int $id): bool
    {
        $agency = $this->findById($id);
        if ($agency && $agency->logo) {
            Storage::disk('public')->delete($agency->logo);
        }
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        $agencies = $this->model->whereIn('id', $ids)->get();
        foreach ($agencies as $agency) {
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }
        }
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?Agency
    {
        return $this->model->find($id);
    }

    public function searchByName(string $name): Collection
    {
        return $this->model->where('name', 'like', "%{$name}%")->get();
    }

    public function getCount(): int
    {
        return $this->model->count();
    }

    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->latest()->take($limit)->get();
    }

    public function updateStatus(int $id, bool $isActive): Agency
    {
        $agency = $this->findById($id);
        $agency->is_active = $isActive;
        $agency->save();
        return $agency;
    }

    public function addLogo(int $id, array $fileData): Agency
    {
        $agency = $this->findById($id);
        
        if (isset($fileData['logo'])) {
            // Eski logoyu sil
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }

            $file = $fileData['logo'];
            $path = $file->store('agencies', 'public');
            
            $agency->update([
                'logo' => $path
            ]);
        }

        return $agency;
    }
} 