<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackupCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $backupFiles;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $backupFiles)
    {
        $this->userId = $userId;
        $this->backupFiles = $backupFiles;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('backup.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'backup.completed';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Yedekleme başarıyla tamamlandı.',
            'files' => $this->backupFiles
        ];
    }
}
