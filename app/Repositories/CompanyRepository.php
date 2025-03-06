<?php

namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyRepository implements CompanyRepositoryInterface
{
    protected $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate(10);
    }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Company
    {
        $company = $this->findById($id);
        $company->update($data);
        return $company;
    }

    public function delete(int $id): bool
    {
        $company = $this->findById($id);
        if ($company && $company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        $companies = $this->model->whereIn('id', $ids)->get();
        foreach ($companies as $company) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
        }
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?Company
    {
        return $this->model->find($id);
    }

    public function updateStatus(int $id, bool $isActive): Company
    {
        $company = $this->findById($id);
        $company->is_active = $isActive;
        $company->save();
        return $company;
    }

    public function addLogo(int $id, array $fileData): Company
    {
        $company = $this->findById($id);
        
        if (isset($fileData['logo'])) {
            // Eski logoyu sil
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $file = $fileData['logo'];
            $path = $file->store('companies', 'public');
            
            $company->update([
                'logo' => $path
            ]);
        }

        return $company;
    }

    public function search(string $query): array
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->toArray();
    }
} 