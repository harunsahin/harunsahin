<?php

namespace App\Services\Module;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ModuleStateService
{
    protected $state;
    protected $processId;

    public function initialize(string $processId): void
    {
        $this->processId = $processId;
        $this->state = [
            'process_id' => $processId,
            'started_at' => now(),
            'current_step' => 'initialized',
            'steps_completed' => [],
            'updated_at' => now()
        ];

        $this->saveState();
    }

    public function update(string $step): void
    {
        $this->loadState();
        
        $this->state['steps_completed'][] = $step;
        $this->state['current_step'] = $step;
        $this->state['updated_at'] = now();

        $this->saveState();
        
        \Log::info("Modül durumu güncellendi", [
            'process_id' => $this->processId,
            'step' => $step
        ]);
    }

    public function getCurrentState(): array
    {
        $this->loadState();
        return $this->state ?? [
            'process_id' => $this->processId,
            'current_step' => 'unknown',
            'steps_completed' => [],
            'error' => 'State not found'
        ];
    }

    public function getDuration(): int
    {
        $this->loadState();
        return Carbon::parse($this->state['started_at'])
            ->diffInSeconds(Carbon::parse($this->state['updated_at']));
    }

    protected function saveState(): void
    {
        if ($this->processId) {
            Cache::put(
                "module_state_{$this->processId}",
                $this->state,
                now()->addHours(1)
            );
        }
    }

    protected function loadState(): void
    {
        if ($this->processId && !$this->state) {
            $this->state = Cache::get("module_state_{$this->processId}");
        }
    }

    public function cleanup(): void
    {
        if ($this->processId) {
            Cache::forget("module_state_{$this->processId}");
        }
    }
} 