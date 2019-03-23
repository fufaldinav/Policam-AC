<?php
namespace ORM;

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
class Controllers extends Entries
{
    /** @var array Связи many_to_one */
    protected $belongs_to = [
      'organization' => [
        'class' => 'organizations',
        'foreign_key' => 'org_id'
      ]
    ];

    /** @var array Связи one_to_many */
    protected $has_many = [
      'tasks' => [
        'class' => 'tasks',
        'foreign_key' => 'controller_id'
      ]
    ];

    /** @var string Имя контроллера */
    public $name = 'Без имени';

    /** @var int Серийный номер */
    public $sn;

    /** @var string Модель контроллера */
    public $type = 'Z5RWEB';

    /** @var string Версия прошивки контроллера */
    public $fw = null;

    /** @var string Версия прошивки модуля связи */
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

    /** @var string IP адрес контроллера в локальной сети */
    public $ip = null;

    /** @var int Признак активированности контроллера */
    public $active = 1;

    /** @var int ONLINE проверка доступа */
    public $online = 0;

    /** @var int Последнее соединение контроллера с сервером */
    public $last_conn = 0;

    /** @var int Организация, в которой установлен контроллер */
    public $org_id = 0;
}
