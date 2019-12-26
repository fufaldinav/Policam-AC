<?php

namespace App\Events;

use App\Device;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PolicamSlavePowerOn
{
    use Dispatchable, SerializesModels;

    public $device;

    /**
     * Create a new event instance.
     *
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
    }
}
