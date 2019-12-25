<?php

namespace App\Events;

use App\Controller;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class DeviceChangedStatus
{
    use Dispatchable, SerializesModels;

    public $controller;

    /**
     * Create a new event instance.
     *
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }
}
