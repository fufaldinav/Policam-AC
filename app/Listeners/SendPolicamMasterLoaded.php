<?php

namespace App\Listeners;

use App;
use App\Events\PolicamMasterPowerOn;
use App\Notifications\PolicamMasterLoaded;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPolicamMasterLoaded implements ShouldQueue
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
     * @param PolicamMasterPowerOn $event
     * @return void
     */
    public function handle(PolicamMasterPowerOn $event)
    {
        $controller = $event->controller;
        $this->notify(new PolicamMasterLoaded($controller));
    }
}
