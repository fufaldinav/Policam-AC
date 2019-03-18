<?php
/**
 * Name:   Policam AC
 * Author: Artem Fufaldin
 *         artem.fufaldin@gmail.com
 *
 * Created: 18.03.2019
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
 * Class Cards
 */
class Cards extends MicroORM
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

    /**
     * Признак активированности карты
     *
     * @var int
     */
    public $active = 1;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->table = strtolower(get_class($this));

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }
}
