<?php

namespace App\Http\Traits;

trait ApiResponse
{
    protected function successResponse($message, $data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
} 