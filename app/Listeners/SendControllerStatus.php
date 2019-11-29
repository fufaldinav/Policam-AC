<?php

namespace App\Listener;

use App\Events\ControllerConnected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendControllerStatus
{
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
        //
    }
}
