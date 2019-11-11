<?php

namespace App\Events;

use App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PingReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ctrlId;
    public $devices;

    /**
     * Create a new event instance.
     *
     * @param int $ctrlId
     * @param $devices
     */
    public function __construct(int $ctrlId, $devices = null)
    {
        $this->ctrlId = $ctrlId;
        $this->devices = $devices;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('controller-events.' . $this->ctrlId);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $devices = [];
        if (is_array($this->devices)) {
            foreach ($this->devices as $device) {
                $devices[$device->id] = $device->timeout;
            }
        } else if (isset($this->devices)) {
            $devices = $this->devices;
        }
        return [
            'controller_id' => $this->ctrlId,
            'devices' => $devices,
        ];
    }
}
