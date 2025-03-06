<?php

namespace App\Services;

use App\Models\KaynakTanim;
use Illuminate\Support\Collection;

class KaynakTanimlariService
{
    public function getAll(): Collection
    {
        return KaynakTanim::where('is_active', true)
            ->orderBy('position', 'desc')
            ->get();
    }

    public function find(int $id): ?KaynakTanim
    {
        return KaynakTanim::find($id);
    }

    public function create(array $data): KaynakTanim
    {
        return KaynakTanim::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return KaynakTanim::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return KaynakTanim::where('id', $id)->delete();
    }
} 