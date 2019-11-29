<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ControllerStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $controller;
    public $devices;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $controller
     * @param $user
     */
    public function __construct($controller, $user)
    {
        $this->controller = $controller;
        $this->devices = json_decode($controller->devices_status);
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.controller-status');
    }
}
