<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 28.03.2019
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
namespace App\Policam\Ac\Z5RWEB;


final class Response implements \JsonSerializable
{
    public $date;
    protected $interval = 10;
    protected $messages = [];

    public function __construct(int $time = null)
    {
        $this->date = date('Y-m-d H:i:s', $time ?? time());
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->date,
            'interval' => $this->interval,
            'messages' => $this->messages,
        ];
    }

    public function addMessage(OutgoingMessage $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages()
    {
        return json_encode($this);
    }
}
