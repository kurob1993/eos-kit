<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Absence;

class SendingAbsenceToSap
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    protected $absence;

    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
