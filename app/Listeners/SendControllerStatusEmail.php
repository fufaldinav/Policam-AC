<?php

namespace App\Listeners;

use App;
use App\Events\ControllerChangedStatus;
use App\Mail\ControllerStatusEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendControllerStatusEmail
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
     * @param ControllerChangedStatus $event
     * @return void
     */
    public function handle(ControllerChangedStatus $event)
    {
        $controller = $event->controller;
        $organization = App\Organization::find($controller->organization_id);

        foreach ($organization->users as $user) {
            if ($user->hasRole(1)) {
                \Mail::to($user->email)->send(
                    new ControllerStatusEmail($event->controller, $user)
                );
            }
        }
    }
}
