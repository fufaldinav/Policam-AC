<?php

namespace App\Notifications;

use App\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class PolicamSlaveLoaded extends Notification
{
    use Queueable;

    public $device;

    /**
     * Create a new notification instance.
     *
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
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
        $content = 'Slave ' . $this->device->name . ' загрузился' . PHP_EOL;

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to(577532899)
            // Markdown supported.
            ->content($content);
    }
}
