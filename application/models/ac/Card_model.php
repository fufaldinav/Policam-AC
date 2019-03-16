<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 01.03.2019
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
 * Class Card Model
 */
class Card_model extends MY_Model
{
    /**
     * Код карты
     *
     * @var string
     */
    public $wiegand;

    /**
     * Время последней попытки доступа
     *
     * @var int
     */
    public $last_conn;

    /**
     * Контроллер, на котором была совершена попытка доступа
     *
     * @var int
     */
    public $controller_id;

    /**
     * Владелец карты
     *
     * @var int
     */
    public $person_id = 0;

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'cards';
        $this->_foreing_key = 'person_id';
    }

    /**
     * Выделяет нужные для записи в БД свойства
     *
     * @return mixed[] Массив с параметрами карты
     */
    protected function _get_array(): array
    {
        $data = [
            'wiegand' => $this->wiegand,
            'last_conn' => $this->last_conn,
            'controller_id' => $this->controller_id,
            'person_id' => $this->person_id
        ];

        return $data;
    }
}
