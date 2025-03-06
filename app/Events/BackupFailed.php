<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackupFailed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $error;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $error)
    {
        $this->userId = $userId;
        $this->error = $error;
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
        return 'backup.failed';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'Yedekleme işlemi başarısız oldu.',
            'error' => $this->error
        ];
    }
}
