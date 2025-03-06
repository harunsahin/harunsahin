<?php

namespace App\Services\Module\Results;

class ModuleGenerationResult
{
    public function __construct(
        private string $name,
        private string $processId,
        private float $duration,
        private array $state = []
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getProcessId(): string
    {
        return $this->processId;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function getState(): array
    {
        return $this->state;
    }

    public function toArray(): array
    {
        return [
            'success' => true,
            'message' => "{$this->name} modülü başarıyla oluşturuldu",
            'data' => [
                'module' => $this->name,
                'process_id' => $this->processId,
                'duration' => round($this->duration, 2) . ' saniye',
                'state' => $this->state
            ]
        ];
    }
} 