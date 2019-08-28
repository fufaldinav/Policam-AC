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

namespace App\Policam\Ac;

use App;
use App\Policam\Ac\Z5RWEB\OutgoingMessage as Z5RWEBOutgoingMessage;
use App\Policam\Ac\Z5RWEB\Card as Z5RWEBCard;
use App\Policam\Ac\Policont\OutgoingMessage as PolicontOutgoingMessage;
use App\Policam\Ac\Policont\Card  as PolicontCard;

class Tasker
{
    /**
     * Тело запроса, отправляемого на контроллер
     *
     * @var object
     */
    protected $message;

    /**
     * Задания на отправку
     *
     * @var object
     */
    protected $to_send = [];

    /**
     * Отправляет задания
     *
     * @return int Количество отправленных заданий
     */
    public function send(): int
    {
        $counter = 0;

        foreach ($this->to_send as $task) {
            $counter += $task->save();
        }

        $this->to_send = [];

        return $counter;
    }

    /**
     * Добавляет задание на отправку
     *
     * @param int $ctrl_id ID контроллера, для которого предназначено задание
     *
     * @return bool TRUE - успешное добавление, FALSE - ошибка
     */
    public function add(int $ctrl_id): bool
    {
        if (! $this->message) {
            return false;
        }

        $task = new App\Task;

        $task->task_id = $this->message->generateId();
        $task->controller_id = $ctrl_id;
        $task->json = $this->message;

        $this->to_send[] = $task;

        return true;
    }

    /**
     * Устанавливает параметры открывания и контроля состояния двери
     *
     * @param int $open
     * @param int $open_control
     * @param int $close_control
     *
     * @return void
     */
    public function setDoorParams(int $open, int $open_control = 30, int $close_control = 30): void
    {
        $this->message = new OutgoingMessage();

        $this->message->setOperation('set_door_params');
        $this->message->setOpenTime($open);
        $this->message->setOpenControl($open_control);
        $this->message->setCloseControl($close_control);
    }

    /**
     * Добавляет карты в память контроллера. Если в памяти контроллера уже
     * имеется карта с таким-же номером, для этой карты обновляются флаги и
     * временные зоны.
     *
     * @param string $ctrlType
     * @param string[] $codes Коды карт
     *
     * @return void
     */
    public function addCards(string $ctrlType, array $codes): void
    {
        if ($ctrlType == 'Z5RWEB')
        {
            $this->message = new Z5RWEBOutgoingMessage();
        }
        else
        {
            $this->message = new PolicontOutgoingMessage();
        }

        $this->message->setOperation('add_cards');

        foreach ($codes as $code) {
            if ($ctrlType == 'Z5RWEB')
            {
                $card = new Z5RWEBCard($code);
            }
            else
            {
                $card = new PolicontCard($code);
            }
            $card->setActive();

            $this->message->addCard($card);
        }
    }

    /**
     * Удаляет карты из памяти контроллера
     *
     * @param string $ctrlType Тип контроллера
     * @param string[] $codes Коды карт
     *
     * @return void
     */
    public function delCards(string $ctrlType, array $codes): void
    {
        if ($ctrlType == 'Z5RWEB')
        {
            $this->message = new Z5RWEBOutgoingMessage();
        }
        else
        {
            $this->message = new PolicontOutgoingMessage();
        }

        $this->message->setOperation('del_cards');

        foreach ($codes as $code) {
            if ($ctrlType == 'Z5RWEB')
            {
                $card = new Z5RWEBCard($code);
            }
            else
            {
                $card = new PolicontCard($code);
            }

            $this->message->addCard($card);
        }
    }

    /**
     * Удаляет все карты из памяти контроллера
     *
     * @param string $ctrlType
     * @return void
     */
    public function clearCards(string $ctrlType): void
    {
        if ($ctrlType == 'Z5RWEB')
        {
            $this->message = new Z5RWEBOutgoingMessage();
        }
        else
        {
            $this->message = new PolicontOutgoingMessage();
        }

        $this->message->setOperation('clear_cards');
    }
}
