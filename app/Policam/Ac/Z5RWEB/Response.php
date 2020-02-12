<?php
/**
 * Name:   Policam AC
 *
 * Created: 28.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 */

namespace App\Policam\Ac\Z5RWEB;

use Carbon\Carbon;
use JsonSerializable;

final class Response implements JsonSerializable
{
    public $datetime;
    protected $interval = 10;
    protected $messages = [];

    public function __construct(string $datetime = null)
    {
        $this->datetime = $datetime ?? Carbon::now()->toDateTimeString();
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->datetime,
            'interval' => $this->interval,
            'messages' => $this->messages,
        ];
    }

    public function addMessage($message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages()
    {
        return json_encode($this);
    }
}
