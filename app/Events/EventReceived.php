<?php

namespace App\Events;

use App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class EventReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    /**
     * Create a new event instance.
     *
     * @param App\Event $event
     *
     * @return void
     */
    public function __construct(App\Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('controller-events.' . $this->event->controller_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $event = $this->event;
        $card = App\Card::find($event->card_id);

        if ($event->event === 2 || $event->event === 3) {
            return [
                'card' => $card,
                'event' => $event,
            ];
        } elseif ($event->event === 4 || $event->event === 5 || $event->event === 16 || $event->event === 17) {
            return [
                'person_id' => $card->person->id,
                'event' => $event,
            ];
        } else {
            return [
                'event' => $event,
            ];
        }
    }
}
