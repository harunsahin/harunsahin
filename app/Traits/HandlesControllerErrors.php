<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HandlesControllerErrors
{
    /**
     * İşlem başarılı olduğunda JSON yanıtı döndürür
     */
    protected function successResponse($data = [], $message = 'İşlem başarılı.', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * İşlem başarısız olduğunda JSON yanıtı döndürür
     */
    protected function errorResponse($message = 'Bir hata oluştu.', $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Try-catch bloğunu yönetir ve hataları loglar
     */
    protected function handleControllerAction($action, $errorMessage = 'Bir hata oluştu.')
    {
        try {
            return $action();
        } catch (\Exception $e) {
            Log::error($errorMessage . ': ' . $e->getMessage());
            return $this->errorResponse($errorMessage);
        }
    }
} 