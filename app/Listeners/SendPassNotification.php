<?php

namespace App\Listeners;

use App\Events\EventReceived;
use App\Policam\Ac\Notificator;

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
