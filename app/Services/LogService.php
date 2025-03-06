<?php

namespace App\Services;

use App\Interfaces\LogRepositoryInterface;
use App\Interfaces\LogServiceInterface;
use App\Models\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log as LogFacade;

class LogService implements LogServiceInterface
{
    protected $repository;

    public function __construct(LogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        try {
            return $this->repository->getAll();
        } catch (\Exception $e) {
            LogFacade::error('Log listesi alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): Log
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            LogFacade::error('Log oluşturulurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): Log
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            LogFacade::error('Log güncellenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            LogFacade::error('Log silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            return $this->repository->bulkDelete($ids);
        } catch (\Exception $e) {
            LogFacade::error('Loglar toplu silinirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?Log
    {
        try {
            return $this->repository->findById($id);
        } catch (\Exception $e) {
            LogFacade::error('Log bulunurken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByLevel(string $level): LengthAwarePaginator
    {
        try {
            return $this->repository->getByLevel($level);
        } catch (\Exception $e) {
            LogFacade::error('Seviyeye göre loglar alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        try {
            return $this->repository->getByDateRange($startDate, $endDate);
        } catch (\Exception $e) {
            LogFacade::error('Tarih aralığına göre loglar alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByUser(int $userId): LengthAwarePaginator
    {
        try {
            return $this->repository->getByUser($userId);
        } catch (\Exception $e) {
            LogFacade::error('Kullanıcıya göre loglar alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getByModule(string $module): LengthAwarePaginator
    {
        try {
            return $this->repository->getByModule($module);
        } catch (\Exception $e) {
            LogFacade::error('Modüle göre loglar alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getLatest(int $limit = 10): array
    {
        try {
            return $this->repository->getLatest($limit);
        } catch (\Exception $e) {
            LogFacade::error('Son loglar alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getErrors(): LengthAwarePaginator
    {
        try {
            return $this->repository->getErrors();
        } catch (\Exception $e) {
            LogFacade::error('Hata logları alınırken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function clearLogs(): bool
    {
        try {
            return $this->repository->clearLogs();
        } catch (\Exception $e) {
            LogFacade::error('Loglar temizlenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function clearLogsBefore(string $date): bool
    {
        try {
            return $this->repository->clearLogsBefore($date);
        } catch (\Exception $e) {
            LogFacade::error('Belirli tarihten önceki loglar temizlenirken hata oluştu: ' . $e->getMessage());
            throw $e;
        }
    }

    public function error(string $message, array $context = []): void
    {
        try {
            $this->create([
                'name' => 'Hata',
                'level' => 'error',
                'message' => $message,
                'context' => $context,
                'path' => request()->path(),
                'method' => request()->method(),
                'ip' => request()->ip()
            ]);
        } catch (\Exception $e) {
            LogFacade::error('Hata logu oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }
} 