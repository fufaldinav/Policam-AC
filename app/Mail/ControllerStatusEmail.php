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
