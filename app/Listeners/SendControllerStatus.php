<?php

namespace App\Listeners;

use App\Events\ControllerConnected;
use App\Notifications\DeviceChangedStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendControllerStatus
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
     * @param  ControllerConnected  $event
     * @return void
     */
    public function handle(ControllerConnected $event)
    {
//        $this->notify(new DeviceChangedStatus());
    }
}
