<?php

namespace App\Services;

use App\Interfaces\CommentServiceInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Interfaces\ActivityServiceInterface;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentService implements CommentServiceInterface
{
    protected $commentRepository;
    protected $activityService;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        ActivityServiceInterface $activityService
    ) {
        $this->commentRepository = $commentRepository;
        $this->activityService = $activityService;
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            // SQL sorgularını loglayalım
            \DB::enableQueryLog();
            
            $comments = $this->commentRepository->getAll();
            
            // SQL sorgularını loglayalım
            \Log::info('CommentService SQL Sorguları:', [
                'queries' => \DB::getQueryLog()
            ]);
            
            // Debug için detaylı log ekleyelim
            \Log::info('CommentService: Yorumlar listeleniyor', [
                'count' => $comments->count(),
                'total' => $comments->total(),
                'has_more_pages' => $comments->hasMorePages(),
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'data' => $comments->toArray()
            ]);
            
            return $comments;
        } catch (\Exception $e) {
            \Log::error('CommentService: Yorumlar listelenirken hata: ' . $e->getMessage());
            \Log::error('CommentService: Yorumlar listelenirken hata detayı: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function find(int $id): ?Comment
    {
        try {
            if (!$id) {
                throw new \InvalidArgumentException('Geçersiz yorum ID\'si.');
            }

            $comment = $this->commentRepository->find($id);
            
            if (!$comment) {
                throw new \Exception('Yorum bulunamadı.');
            }
            
            Log::info('Yorum detayı alındı', [
                'comment_id' => $id,
                'data' => $comment->toArray()
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            Log::error('Yorum detayı alınırken hata oluştu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'comment_id' => $id
            ]);
            throw $e;
        }
    }

    public function create(array $data): Comment
    {
        try {
            // SQL sorgularını loglayalım
            \DB::enableQueryLog();
            
            // Validasyon kontrolü
            if (empty($data['comment'])) {
                throw new \InvalidArgumentException('Yorum alanı boş bırakılamaz.');
            }

            \Log::info('CommentService: Yorum oluşturma başlıyor', [
                'data' => $data
            ]);

            DB::beginTransaction();

            $comment = $this->commentRepository->create($data);

            \Log::info('CommentService: Yorum başarıyla oluşturuldu', [
                'comment_id' => $comment->id,
                'data' => $comment->toArray()
            ]);

            $this->activityService->create([
                'user_id' => $data['created_by'],
                'action' => 'create',
                'module' => 'comments',
                'comment_id' => $comment->id,
                'old_values' => null,
                'new_values' => $comment->toArray()
            ]);

            DB::commit();
            
            // SQL sorgularını loglayalım
            \Log::info('CommentService SQL Sorguları:', [
                'queries' => \DB::getQueryLog()
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CommentService: Yorum oluşturulurken hata:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Comment
    {
        try {
            DB::beginTransaction();

            $comment = $this->commentRepository->update($id, $data);

            $this->activityService->create([
                'user_id' => $data['updated_by'],
                'action' => 'update',
                'module' => 'comments',
                'comment_id' => $comment->id,
                'old_values' => $comment->getOriginal(),
                'new_values' => $comment->toArray()
            ]);

            DB::commit();
            
            Log::info('Yorum güncellendi', [
                'comment_id' => $id,
                'data' => $data
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Yorum güncellenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $comment = $this->commentRepository->findById($id);
            $result = $this->commentRepository->delete($id);

            $this->activityService->create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'module' => 'comments',
                'comment_id' => $id,
                'old_values' => $comment->toArray(),
                'new_values' => null
            ]);

            DB::commit();
            
            Log::info('Yorum silindi', [
                'comment_id' => $id
            ]);
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Yorum silinirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            DB::beginTransaction();

            $comments = $this->commentRepository->findByIds($ids);
            $result = $this->commentRepository->bulkDelete($ids);

            foreach ($comments as $comment) {
                $this->activityService->create([
                    'user_id' => auth()->id(),
                    'action' => 'delete',
                    'module' => 'comments',
                    'comment_id' => $comment->id,
                    'old_values' => $comment->toArray(),
                    'new_values' => null
                ]);
            }

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Toplu yorum silme işlemi başarısız: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?Comment
    {
        try {
            return $this->commentRepository->findById($id);
        } catch (\Exception $e) {
            Log::error('Yorum bulunurken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        try {
            return $this->commentRepository->getByUser($userId);
        } catch (\Exception $e) {
            Log::error('Kullanıcı yorumları listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getApproved(): LengthAwarePaginator
    {
        try {
            return $this->commentRepository->getApproved();
        } catch (\Exception $e) {
            Log::error('Onaylı yorumlar listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPending(): LengthAwarePaginator
    {
        try {
            return $this->commentRepository->getPending();
        } catch (\Exception $e) {
            Log::error('Bekleyen yorumlar listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getRejected(): LengthAwarePaginator
    {
        try {
            return $this->commentRepository->getRejected();
        } catch (\Exception $e) {
            Log::error('Reddedilen yorumlar listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateStatus(int $id, bool $status): Comment
    {
        try {
            DB::beginTransaction();

            $comment = $this->commentRepository->findById($id);
            if (!$comment) {
                throw new \Exception('Yorum bulunamadı.');
            }

            $oldValues = $comment->toArray();
            $comment->status = $status;
            $comment->save();

            $this->activityService->create([
                'user_id' => auth()->id(),
                'action' => 'update_status',
                'module' => 'comments',
                'comment_id' => $id,
                'old_values' => $oldValues,
                'new_values' => $comment->toArray()
            ]);

            DB::commit();
            
            Log::info('Yorum durumu güncellendi', [
                'comment_id' => $id,
                'new_status' => $status
            ]);
            
            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Yorum durumu güncellenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        try {
            $paginator = $this->commentRepository->getByDateRange($startDate, $endDate);
            return $paginator->getCollection();
        } catch (\Exception $e) {
            Log::error('Tarih aralığına göre yorumlar listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getLatest(int $limit = 10): Collection
    {
        try {
            return $this->commentRepository->getLatest($limit);
        } catch (\Exception $e) {
            Log::error('Son yorumlar listelenirken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getCount(): int
    {
        try {
            return $this->commentRepository->getCount();
        } catch (\Exception $e) {
            Log::error('Yorum sayısı alınırken hata: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserCommentCount(int $userId): int
    {
        try {
            return $this->commentRepository->getUserCommentCount($userId);
        } catch (\Exception $e) {
            Log::error('Kullanıcı yorum sayısı alınırken hata: ' . $e->getMessage());
            throw $e;
        }
    }
} 