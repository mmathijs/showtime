<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAllActs implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $currentAct;
    public $allActs;

    public $currentDay;
    public $allDays;


    /**
     * Create a new event instance.
     */
    public function __construct($currentAct, $allActs, $currentDay, $allDays)
    {
        $this->currentAct = $currentAct;
        $this->allActs = $allActs;

        $this->currentDay = $currentDay;
        $this->allDays = $allDays;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('act-update');
    }
}
