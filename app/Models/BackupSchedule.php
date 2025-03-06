<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BackupSchedule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'compress',
        'notify',
        'schedule_type',
        'cron_expression',
        'next_run',
        'last_run',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'compress' => 'boolean',
        'notify' => 'boolean',
        'is_active' => 'boolean',
        'next_run' => 'datetime',
        'last_run' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateNextRun()
    {
        $cron = new \Cron\CronExpression($this->cron_expression);
        $this->next_run = $cron->getNextRunDate();
        $this->save();
    }
}
