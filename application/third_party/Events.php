<?php
namespace Orm;

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
 * Class Events
 */
class Events extends MicroORM
{
    /**
     * @var array
     */
    protected $_belongs_to = [
      'controller' => [
        'class' => 'controllers',
        'foreign_key' => 'controller_id'
      ],
      'card' => [
        'class' => 'cards',
        'foreign_key' => 'card_id'
      ]
    ];

    /**
     * Контроллер, от которого пришло событие
     *
     * @var int
     */
    public $controller_id;

    /**
     * Тип события
     *
     * @var int
     */
    public $event;

    /**
     * Флаг события
     *
     * @var int
     */
    public $flag;

    /**
     * Время события на контроллере
     *
     * @var int
     */
    public $time;

    /**
     * Время получения события сервером
     *
     * @var int
     */
    public $server_time;

    /**
     * Карта, вызвавшая событие
     *
     * @var int
     */
    public $card_id;

    public function __construct($param = null)
    {
        parent::__construct();

        $this->_table = strtolower((new \ReflectionClass($this))->getShortName());

        if (is_numeric($param)) {
            $this->get($param);
        } elseif (is_array($param)) {
            $this->get_by($param);
        }
    }
}
