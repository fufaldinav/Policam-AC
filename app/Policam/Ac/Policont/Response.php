<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 09.07.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.3 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */

namespace App\Policam\Ac\Policont;

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
