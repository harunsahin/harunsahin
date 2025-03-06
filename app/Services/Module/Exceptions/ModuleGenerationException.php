<?php

namespace App\Services\Module\Exceptions;

use Exception;
use Throwable;

class ModuleGenerationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
        \Log::error('Modül oluşturma hatası: ' . $message, [
            'code' => $code,
            'trace' => $this->getTraceAsString()
        ]);
    }
} 