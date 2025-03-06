<?php

namespace App\Traits;

use App\Models\Status;
use Illuminate\Support\Facades\Cache;

trait HasStatus
{
    public static function getActiveStatuses()
    {
        return Cache::remember('active_statuses', 60, function () {
            return Status::where('is_active', true)
                        ->orderBy('name')
                        ->get();
        });
    }

    public function getStatusBadgeHtml()
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $this->status_color,
            htmlspecialchars($this->status_name, ENT_QUOTES)
        );
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->status->name ?? 'Belirsiz';
    }

    public function getStatusColorAttribute()
    {
        return $this->status->color ?? 'secondary';
    }

    public function updateStatus($statusId)
    {
        $this->update(['status_id' => $statusId]);
        Cache::tags(['statuses'])->flush();
    }
} 