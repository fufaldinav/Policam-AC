<?php

namespace App\Listeners;

use App;
use App\Events\DeviceChangedStatus;
use App\Mail\ControllerStatusEmail;
use App\Notifications\DeviceChangedStatus as DeviceChangedStatusNotification;
use Illuminate\Notifications\Notifiable;

class SendDeviceStatus
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
     * @param DeviceChangedStatus $event
     * @return void
     */
    public function handle(DeviceChangedStatus $event)
    {
        $controller = $event->controller;
        $organization = App\Organization::find($controller->organization_id);

        foreach ($organization->users as $user) {
            if ($user->hasRole(1)) {
                \Mail::to($user->email)->send(
                    new ControllerStatusEmail($controller, $user)
                );
            }
        }

        $this->notify(new DeviceChangedStatusNotification($controller));
    }
}
