<?php

namespace App\Repositories;

use App\Interfaces\OfferRepositoryInterface;
use App\Models\Offer;
use App\Models\OfferFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfferRepository implements OfferRepositoryInterface
{
    protected $model;

    public function __construct(Offer $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->model->with(['status', 'company', 'agency'])->latest()->paginate(10);
    }

    public function create(array $data): Offer
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Offer
    {
        $offer = $this->findById($id);
        $offer->update($data);
        return $offer;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?Offer
    {
        return $this->model->with(['status', 'company', 'agency', 'files'])->find($id);
    }

    public function updateStatus(int $id, int $statusId): Offer
    {
        $offer = $this->findById($id);
        $offer->status_id = $statusId;
        $offer->save();
        return $offer;
    }

    public function addFile(int $id, array $fileData): Offer
    {
        $offer = $this->findById($id);
        
        if (isset($fileData['file'])) {
            $file = $fileData['file'];
            $path = $file->store('offers', 'public');
            
            $offer->files()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize()
            ]);
        }

        return $offer;
    }
} 