<?php

namespace App\Services\Module;

class ModuleGenerationResult
{
    public function __construct(
        public readonly string $moduleName,
        public readonly string $processId,
        public readonly float $duration,
        public readonly array $state = []
    ) {}

    public function toArray(): array
    {
        return [
            'success' => true,
            'message' => "{$this->moduleName} modülü başarıyla oluşturuldu",
            'data' => [
                'module' => $this->moduleName,
                'process_id' => $this->processId,
                'duration' => round($this->duration, 2) . ' saniye',
                'state' => $this->state
            ]
        ];
    }
} 