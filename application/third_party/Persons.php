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
 * Class Persons
 */
class Persons extends MicroORM
{
    /**
     * Фамилия
     *
     * @var string
     */
    public $f;

    /**
     * Имя
     *
     * @var string
     */
    public $i;

    /**
     * Отвество
     *
     * @var string
     */
    public $o;

    /**
     * Тип/должность человека
     *
     * @var int
     */
    public $type = 1;

    /**
     * Дата рождения
     *
     * @var string
     */
    public $birthday;

    /**
     * Адрес
     *
     * @var string
     */
    public $address;

    /**
     * Номер телефона
     *
     * @var string
     */
    public $phone;

    /**
     * Карты
     *
     * @var array
     */
    private $cards = [];

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
