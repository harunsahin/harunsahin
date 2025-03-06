<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            $comments = $this->model->with(['creator', 'updater'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Debug için log ekleyelim
            \Log::info('CommentRepository: Yorumlar listeleniyor', [
                'count' => $comments->count(),
                'total' => $comments->total(),
                'data' => $comments->toArray()
            ]);
            
            return $comments;
        } catch (\Exception $e) {
            \Log::error('CommentRepository: Yorumlar listelenirken hata: ' . $e->getMessage());
            \Log::error('CommentRepository: Yorumlar listelenirken hata detayı: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function find(int $id): ?Comment
    {
        try {
            $comment = $this->model->with(['creator', 'updater'])->find($id);
            
            \Log::info('Yorum detayı alındı', [
                'comment_id' => $id,
                'data' => $comment ? $comment->toArray() : null
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            \Log::error('Yorum detayı alınırken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function create(array $data): Comment
    {
        try {
            $comment = $this->model->create($data);
            
            \Log::info('Yeni yorum oluşturuldu', [
                'comment_id' => $comment->id,
                'data' => $comment->toArray()
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            \Log::error('Yorum oluşturulurken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Comment
    {
        try {
            $comment = $this->model->findOrFail($id);
            $comment->update($data);
            
            \Log::info('Yorum güncellendi', [
                'comment_id' => $id,
                'data' => $comment->toArray()
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            \Log::error('Yorum güncellenirken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $result = $this->model->where('id', $id)->delete();
            
            \Log::info('Yorum silindi', [
                'comment_id' => $id
            ]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('Yorum silinirken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function findById(int $id): ?Comment
    {
        return $this->model->with(['creator', 'updater'])->find($id);
    }

    public function findByIds(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        return $this->model->where('created_by', $userId)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getApproved(): LengthAwarePaginator
    {
        return $this->model->where('status', true)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getPending(): LengthAwarePaginator
    {
        return $this->model->where('status', false)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getRejected(): LengthAwarePaginator
    {
        return $this->model->where('status', false)
            ->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $comment = $this->model->findOrFail($id);
        return $comment->update(['status' => $status]);
    }

    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        return $this->model->whereBetween('comment_date', [$startDate, $endDate])
            ->with(['creator', 'updater'])
            ->orderBy('comment_date', 'desc')
            ->paginate(10);
    }

    public function getLatest(int $limit = 10): array
    {
        return $this->model->with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getCount(): int
    {
        return $this->model->count();
    }

    public function getUserCommentCount(int $userId): int
    {
        return $this->model->where('created_by', $userId)->count();
    }
} 