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
 * Class Tasks
 */
class Tasks extends MicroORM
{
    /**
     * @var array
     */
    protected $_belongs_to = [
      'controller' => [
        'class' => 'controllers',
        'foreign_key' => 'controller_id'
      ]
    ];

    /**
     * ID задания
     *
     * @var int
     */
    public $task_id;
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
}
