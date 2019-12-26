<?php

namespace App\Notifications;

use App\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class PolicamMasterLoaded extends Notification
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
    public function via($notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * @param  mixed  $notifiable
     * @return TelegramMessage
     */
    public function toTelegram($notifiable): TelegramMessage
    {
        $content = 'Контроллер ' . $this->controller->sn . ' загрузился' . PHP_EOL;

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to(577532899)
            // Markdown supported.
            ->content($content);
    }
}
