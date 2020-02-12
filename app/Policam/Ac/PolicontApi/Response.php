<?php
/**
 * Name:   Policam AC
 *
 * Created: 09.07.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 */

namespace App\Policam\Ac\PolicontApi;

use JsonSerializable;

final class Response implements JsonSerializable
{
    public $timestamp;
    protected $interval = 10;
    protected $messages = [];

    public function __construct(int $timestamp = null)
    {
        $this->timestamp = $timestamp ?? time();
    }

    public function jsonSerialize(): array
    {
        return [
            'timestamp' => $this->timestamp,
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
