<?php

namespace App\Listener;

use App\Events\EventReceived;
use App\Policam\Ac\Notificator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPassNotification
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
     * @param EventReceived $event
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle(EventReceived $event)
    {
        $notificator = new Notificator();
        $notificator->handleEvent($event->event); // TODO уведомления
    }
}
