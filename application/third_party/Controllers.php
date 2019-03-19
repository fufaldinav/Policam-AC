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
 * Class Controllers
 */
class Controllers extends MicroORM
{
    protected $_belongs_to = [
      'organization' => [
        'class' => 'organizations',
        'foreign_key' => 'org_id'
      ]
    ];

    /**
     * Имя контроллера
     *
     * @var string
     */
    public $name = 'Без имени';

    /**
     * Серийный номер
     *
     * @var int
     */
    public $sn;

    /**
     * Модель контроллера
     *
     * @var string
     */
    public $type = 'Z5RWEB';

    /**
     * Версия прошивки контроллера
     *
     * @var string
     */
    public $fw = null;

    /**
     * Версия прошивки модуля связи
     *
     * @var string
     */
    public $conn_fw = null;

    /**
     * Режимы: 0 - норма,
     *         1 - блок,
     *         2 - свободный проход,
     *         3 - ожидание свободного прохода
     *
     * @var int
     */
    public $mode = 0;

    /**
     * IP адрес контроллера в локальной сети
     *
     * @var string
     */
    public $ip = null;

    /**
     * Признак активированности контроллера
     *
     * @var int
     */
    public $active = 1;

    /**
     * ONLINE проверка доступа
     *
     * @var int
     */
    public $online = 0;

    /**
     * Последнее соединение контроллера с сервером
     *
     * @var int
     */
    public $last_conn = 0;

    /**
     * Организация, в которой установлен контроллер
     *
     * @var int
     */
    public $org_id = 0;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->_table = strtolower(get_class($this));

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }
}
