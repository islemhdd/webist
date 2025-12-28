<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SortieUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $bat, public array $student) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('sortie-updated.' . $this->bat)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'bat' => $this->bat,
            'student' => $this->student
        ];
    }
}
