<?php

namespace App\Notifications;

use App\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class DeviceChangedStatus extends Notification
{
    use Queueable;

    public $controller;

    /**
     * Create a new notification instance.
     *
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * @param  mixed  $notifiable
     * @return TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        $content = 'Контроллер ' . $this->controller->sn . ' в ' . $this->controller->organization->name . ' изменил статус: ' . PHP_EOL;

        foreach ($this->controller->devices as $id => $device) {
            $content .= 'Slave ' . $id . ' ';
            if ($device->timeout == 0) {
                $content .= 'в сети' . PHP_EOL;
            } else {
                $content .= 'не в сети' . PHP_EOL;
            }
        }

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to(577532899)
            // Markdown supported.
            ->content($content);
    }
}
