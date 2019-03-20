<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 19.03.2019
 *
 * Description: Приложение для систем контроля и управления доступом.
 *
 * Requirements: PHP7.2 or above
 *
 * @package Policam-AC
 * @author  Artem Fufaldin
 * @link    http://github.com/m2jest1c/Policam-AC
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Task
 */
class Task extends Ac
{
    /**
     * Тело запроса, отправляемого на контроллер, после указания всех
     * параметров, перед записью в БД, необходимо конвертировать в JSON формат
     *
     * @var object
     */
    private $_request;

    /**
     * Задания на отправку
     *
     * @var object
     */
    private $_to_send = [];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->_request = new stdClass;
    }

    /**
     * Отправляет задания
     *
     * @return int Количество отправленных заданий
     */
    public function send(): int
    {
        $counter = 0;

        foreach ($this->_to_send as $task) {
            $counter += $task->save();
        }

        $this->_to_send = [];

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
        if (! $this->_request) {
            return false;
        }

        $this->load('Tasks');

        $task = new \Orm\Tasks();

        $task->task_id = $this->_request->id = mt_rand(500000, 999999999);
        $task->controller_id = $ctrl_id;
        $task->json = json_encode($this->_request);
        $task->time = now();

        $this->_to_send[] = $task;

        return true;
    }

    /**
     * Устанавливает параметры открывания и контроля состояния двери
     *
     * @param int $open_time     Время открытия в 0.1 сек
     * @param int $open_control  Контроль открытия в 0.1 сек,
     *                           по-умолчанию 0 - без контроля
     * @param int $close_control Контроль закрытия в 0.1 сек,
     *                           по-умолчанию 0 - без контроля
     *
     * @return void
     */
    public function set_door_params(
        int $open_time,
        int $open_control = 0,
        int $close_control = 0
    ): void {
        $this->_request_clear();

        $this->_request->operation = __FUNCTION__;
        $this->_request->open = $open_time;
        $this->_request->open_control = $open_control;
        $this->_request->close_control = $close_control;
    }

    /**
     * Добавляет карты в память контроллера. Если в памяти контроллера уже
     * имеется карта с таким-же номером, для этой карты обновляются флаги и
     * временные зоны.
     *
     * @param string[] $codes Коды карт
     *
     * @return void
     */
    public function add_cards(array $codes): void
    {
        $this->_request_clear();

        $this->_request->operation = __FUNCTION__;
        $this->_request->cards = [];

        foreach ($codes as $code) {
            $card = new stdClass;
            $card->card = $code;
            $card->flags = 32;
            $card->tz = 255;

            $this->_request->cards[] = $card;
        }
    }

    /**
     * Удаляет карты из памяти контроллера
     *
     * @param string[] $codes Коды карт
     *
     * @return void
     */
    public function del_cards(array $codes): void
    {
        $this->_request_clear();

        $this->_request->operation = __FUNCTION__;
        $this->_request->cards = [];

        foreach ($codes as $code) {
            $card = new stdClass;
            $card->card = $code;

            $this->_request->cards[] = $card;
        }
    }


    /**
     * Удаляет все карты из памяти контроллера
     *
     * @return void
     */
    public function clear_cards(): void
    {
        $this->_request_clear();

        $this->_request->operation = __FUNCTION__;
    }

    /**
     * Очищает тело запроса
     *
     * @return void
     */
    private function _request_clear(): void
    {
        foreach ($this->_request as $key => $value) {
            unset($this->_request->$key);
        }
    }
}
