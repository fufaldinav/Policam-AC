<?php

namespace App\Listeners;

use App;
use App\Events\PolicamSlavePowerOn;
use App\Notifications\PolicamSlaveLoaded;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPolicamSlaveLoaded implements ShouldQueue
{
    use Notifiable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PolicamSlavePowerOn $event
     * @return void
     */
    public function handle(PolicamSlavePowerOn $event)
    {
        $device = $event->device;
        $this->notify(new PolicamSlaveLoaded($device));
    }
}
