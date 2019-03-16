<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 02.03.2019
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
 * Class Task Model
 */
class Task_model extends MY_Model
{
    /**
     * Контроллер, которому предназначено задание
     *
     * @var int
     */
    public $controller_id;

    /**
     * Сообщение в формате JSON
     *
     * @var string
     */
    public $json;

    /**
     * Время формирования сообщения
     *
     * @var int
     */
    public $time;

    /**
     * Тело запроса, отправляемого на контроллер, после указания всех
     * параметров, перед записью в БД, необходимо конвертировать в JSON формат
     *
     * @var object
     */
    private $_request;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'tasks';
        $this->_foreing_key = 'controller_id';

        $this->_request = new stdClass;
    }

    /**
     * Получает последнее задание для контроллера
     *
     * @param int $ctrl_id ID контроллера
     *
     * @return bool TRUE - успешно, FALSE - ошибка
     */
    public function get_last(int $ctrl_id): bool
    {
        $query = $this->CI->db->where($this->_foreing_key, $ctrl_id)
                              ->order_by('time', 'ASC')
                              ->get($this->_table)
                              ->row();

        if (! isset($query)) {
            return false;
        }

        $this->set($query);

        return true;
    }

    /**
     * Сохраняет задание в БД
     *
     * @return int Количество успешных записей
     */
    public function save(): int
    {
        $this->id = $this->_request->id;
        $this->json = json_encode($this->_request);
        $this->time = now('Asia/Yekaterinburg');

        $this->CI->db->insert($this->_table, $this);

        return $this->CI->db->affected_rows();
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

        $this->_request->id = mt_rand(500000, 999999999);
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

        $this->_request->id = mt_rand(500000, 999999999);
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

        $this->_request->id = mt_rand(500000, 999999999);
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

        $this->_request->id = mt_rand(500000, 999999999);
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
